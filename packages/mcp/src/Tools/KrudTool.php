<?php

namespace Icebearsoft\Kitukizuri\Mcp\Tools;

use Icebearsoft\Kitukizuri\Mcp\Authorization\McpPermissionResolver;
use Icebearsoft\Kitukizuri\Mcp\Execution\ResourceService;
use Icebearsoft\Kitukizuri\Mcp\Http\ExecutionContext;
use Icebearsoft\Kitukizuri\Mcp\Metadata\ResourceDefinition;
use Icebearsoft\Kitukizuri\Mcp\Schema\McpSchemaBuilder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Tool;
use Throwable;
use Icebearsoft\Kitukizuri\Mcp\Execution\AuditRecorder;
use Illuminate\Support\Facades\RateLimiter;

class KrudTool extends Tool
{
    public function __construct(public ResourceDefinition $resource, public string $operation) {}

    public function name(): string { return $this->resource->module.'.'.$this->operation; }
    public function title(): string { return ($this->resource->config['title'] ?? $this->resource->module).' / '.$this->operation; }
    public function description(): string { return $this->resource->description($this->operation); }

    public function shouldRegister(Request $request): bool
    {
        $user = $request->user();
        if (!$user || !in_array($this->operation, $this->resource->config['operations'], true)) {
            return false;
        }
        $resolver = app(McpPermissionResolver::class);
        // Stored only in this HTTP request. Execution always re-queries permissions.
        $key = 'kitukizuri.mcp.grants.'.hash('sha256', get_class($user).'|'.$user->getAuthIdentifier());
        if (!request()->attributes->has($key)) {
            app(ExecutionContext::class)->assertUser($user);
            request()->attributes->set($key, $resolver->grants($user));
        }
        return $resolver->allows(request()->attributes->get($key), $this->resource->module, $this->operation);
    }

    public function schema(JsonSchema $schema): array
    {
        return app(McpSchemaBuilder::class)->schema($this->resource, $this->operation, $schema);
    }

    public function annotations(): array
    {
        $read = in_array($this->operation, ['list', 'get'], true);
        return ['readOnlyHint' => $read, 'destructiveHint' => in_array($this->operation, ['update', 'delete'], true),
            'idempotentHint' => $read, 'openWorldHint' => false];
    }

    public function handle(Request $request): Response|ResponseFactory
    {
        $started = microtime(true);
        $status = 'failed';
        try {
            $user = $request->user();
            app(ExecutionContext::class)->assertUser($user);
            if (!in_array($this->operation, $this->resource->config['operations'], true)
                || !app(McpPermissionResolver::class)->can($user, $this->resource->module, $this->operation)) {
                throw new AuthorizationException('Access denied.');
            }
            $key = app(ExecutionContext::class)->rateKey($user, $this->name());
            if (RateLimiter::tooManyAttempts($key, config('kitukizuri-mcp.per_tool_per_minute'))) {
                $status = 'rate_limited';
                return Response::error('Too many tool calls. Try again later.');
            }
            RateLimiter::hit($key, 60);
            $data = app(ResourceService::class)->execute($this->resource, $this->operation, $request->all(), $user);
            $status = 'success';
            return Response::structured(['success' => true, 'resource' => $this->resource->module,
                'operation' => $this->operation, 'data' => $data]);
        } catch (AuthorizationException $e) {
            $status = 'denied';
            return Response::error('Access denied.');
        } catch (ValidationException $e) {
            $status = 'invalid';
            // Custom validation messages can echo inputs: return safe field names only.
            return Response::error('Invalid arguments. Check the tool schema and required fields.');
        } catch (ModelNotFoundException $e) {
            $status = 'not_found';
            return Response::error('Record not found in the current context.');
        } catch (Throwable $e) {
            // Never expose SQL, stack traces or input values, even when app.debug=true.
            return Response::error('Resource operation failed.');
        } finally {
            app(AuditRecorder::class)->record($this->name(), $request->all(), $status, $started);
        }
    }
}
