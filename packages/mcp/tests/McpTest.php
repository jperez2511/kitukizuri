<?php
namespace Icebearsoft\Kitukizuri\Mcp\Tests;

use Icebearsoft\Kitukizuri\Mcp\McpServiceProvider;
use Icebearsoft\Kitukizuri\Mcp\Registry\McpResourceRegistry;
use Icebearsoft\Kitukizuri\Mcp\Servers\KitukizuriServer;
use Icebearsoft\Kitukizuri\Mcp\Tools\KrudTool;
use Icebearsoft\Kitukizuri\Mcp\Tests\Fixtures\Patient;
use Icebearsoft\Kitukizuri\Mcp\Tests\Fixtures\PatientController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Laravel\Mcp\Server\Transport\FakeTransporter;
use Orchestra\Testbench\TestCase;

class McpTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        $package = json_decode(file_get_contents(__DIR__.'/../../../composer.json'), true, flags: JSON_THROW_ON_ERROR);

        return array_merge([\Laravel\Mcp\Server\McpServiceProvider::class], array_values(array_filter(
            $package['extra']['laravel']['providers'],
            fn (string $provider): bool => $provider === McpServiceProvider::class,
        )));
    }
    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', ['driver' => 'sqlite', 'database' => ':memory:']);
        $app['config']->set('kitukizuri.multiTenants', false);
        $app['config']->set('cache.default', 'array');
        $app['config']->set('kitukizuri-mcp.enabled', true);
        $app['config']->set('kitukizuri-mcp.middleware', ['auth:api']);
        $app['config']->set('auth.guards.api', ['driver' => 'token', 'provider' => 'users', 'hash' => true]);
        $app['config']->set('auth.providers.users', ['driver' => 'eloquent', 'model' => User::class]);
    }
    protected function defineRoutes($router): void
    {
        Route::resource('pacientes', PatientController::class);
    }
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('users', function (Blueprint $t) { $t->id(); $t->integer('empresaid')->nullable(); $t->string('api_token')->nullable(); });
        Schema::create('patients', function (Blueprint $t) {
            $t->id(); $t->string('nombre'); $t->date('fecha_nacimiento')->nullable(); $t->integer('empresaid');
            $t->integer('active')->default(1); $t->string('password')->nullable();
            $t->string('remember_token')->nullable(); $t->string('private_note')->nullable();
        });
        foreach ([
            'modulos' => ['moduloid', 'ruta'], 'roles' => ['rolid'],
            'permisos' => ['permisoid', 'nombreLaravel'],
            'usuarioRol' => ['usuariorolid', 'usuarioid', 'rolid'],
            'moduloPermiso' => ['modulopermisoid', 'moduloid', 'permisoid'],
            'rolModuloPermiso' => ['rolmodulopermisoid', 'rolid', 'modulopermisoid'],
            'moduloEmpresas' => ['moduloempresaid', 'moduloid', 'empresaid'],
        ] as $table => $columns) {
            Schema::create($table, function (Blueprint $t) use ($columns) {
                $t->increments(array_shift($columns));
                foreach ($columns as $c) { in_array($c, ['ruta', 'nombreLaravel']) ? $t->string($c) : $t->integer($c); }
            });
        }
        DB::table('modulos')->insert(['moduloid' => 1, 'ruta' => 'pacientes']);
        DB::table('moduloEmpresas')->insert(['moduloid' => 1, 'empresaid' => 1]);
        foreach (['create', 'show', 'edit', 'destroy'] as $i => $action) {
            DB::table('permisos')->insert(['permisoid' => $i + 1, 'nombreLaravel' => $action]);
            DB::table('moduloPermiso')->insert(['modulopermisoid' => $i + 1, 'moduloid' => 1, 'permisoid' => $i + 1]);
        }
        Patient::insert([
            ['id' => 1, 'nombre' => 'Ana', 'empresaid' => 1, 'active' => 1, 'password' => 'secret'],
            ['id' => 2, 'nombre' => 'special', 'empresaid' => 2, 'active' => 1, 'password' => 'secret'],
            ['id' => 3, 'nombre' => 'Inactive', 'empresaid' => 1, 'active' => 0, 'password' => 'secret'],
        ]);
        PatientController::$constructions = 0;
    }
    protected function user(array $permissions = [2], int $company = 1): User
    {
        $id = DB::table('users')->insertGetId(['empresaid' => $company]);
        DB::table('users')->where('id', $id)->update(['api_token' => hash('sha256', 'test-token-'.$id)]);
        DB::table('roles')->insert(['rolid' => $id]);
        DB::table('usuarioRol')->insert(['usuarioid' => $id, 'rolid' => $id]);
        foreach ($permissions as $permission) {
            DB::table('rolModuloPermiso')->insert(['rolid' => $id, 'modulopermisoid' => $permission]);
        }
        return User::findOrFail($id);
    }
    protected function tool(string $operation): KrudTool
    {
        return new KrudTool(app(McpResourceRegistry::class)->resources()['pacientes'], $operation);
    }
    public function test_discovery_uses_existing_permissions_and_never_constructs_unneeded_controllers(): void
    {
        $this->actingAs($this->user());
        $server = new KitukizuriServer(new FakeTransporter);
        $server->start();
        $this->assertSame(['pacientes.list', 'pacientes.get'], $server->createContext()->tools()->map->name()->values()->all());
        $this->assertSame(0, PatientController::$constructions);
        $this->actingAs($this->user([]));
        $this->assertCount(0, $server->createContext()->tools());
    }
    public function test_company_module_assignment_is_required(): void
    {
        $this->actingAs($this->user([2], 2));
        $this->assertFalse($this->tool('list')->shouldRegister(new \Laravel\Mcp\Request));
    }

    public function test_custom_destroy_allows_scoped_reads_but_cannot_be_bypassed(): void
    {
        Route::resource('pacientes', \Icebearsoft\Kitukizuri\Mcp\Tests\Fixtures\CustomDeletePatientController::class);
        $user = $this->user([1, 2, 3, 4]);
        $resource = new \Icebearsoft\Kitukizuri\Mcp\Metadata\ResourceDefinition('pacientes',
            \Icebearsoft\Kitukizuri\Mcp\Tests\Fixtures\CustomDeletePatientController::class,
            PatientController::mcpConfiguration());
        KitukizuriServer::actingAs($user)->tool(new KrudTool($resource, 'list'))
            ->assertOk()->assertSee('Ana')->assertDontSee('special')->assertDontSee('Inactive');
        KitukizuriServer::actingAs($user)->tool(new KrudTool($resource, 'get'), ['id' => '1'])
            ->assertOk()->assertSee('Ana');
        KitukizuriServer::actingAs($user)->tool(new KrudTool($resource, 'get'), ['id' => '2'])->assertHasErrors();
        KitukizuriServer::actingAs($user)->tool(new KrudTool($resource, 'delete'), ['id' => '1'])->assertHasErrors();
        $this->assertSame(3, Patient::count());
    }

    public function test_custom_store_allows_reads_but_blocks_both_insert_and_update(): void
    {
        Route::resource('pacientes', \Icebearsoft\Kitukizuri\Mcp\Tests\Fixtures\CustomStorePatientController::class);
        $user = $this->user([1, 2, 3, 4]);
        $resource = new \Icebearsoft\Kitukizuri\Mcp\Metadata\ResourceDefinition('pacientes',
            \Icebearsoft\Kitukizuri\Mcp\Tests\Fixtures\CustomStorePatientController::class,
            PatientController::mcpConfiguration());
        KitukizuriServer::actingAs($user)->tool(new KrudTool($resource, 'list'))->assertOk()->assertSee('Ana');
        $arguments = ['nombre' => 'Changed', 'fecha_nacimiento' => '2000-01-02'];
        KitukizuriServer::actingAs($user)->tool(new KrudTool($resource, 'create'), $arguments)->assertHasErrors();
        KitukizuriServer::actingAs($user)->tool(new KrudTool($resource, 'update'), ['id' => '1'] + $arguments)->assertHasErrors();
        $this->assertSame(3, Patient::count());
        $this->assertSame('Ana', Patient::findOrFail(1)->nombre);
    }
    public function test_manual_forbidden_call_is_denied_again_in_handler(): void
    {
        $this->actingAs($this->user());
        $response = $this->tool('delete')->handle(new \Laravel\Mcp\Request(['id' => '1']));
        $this->assertTrue($response->isError());
        $this->assertSame('Access denied.', (string) $response->content());
        $this->assertSame(3, Patient::count());
    }
    public function test_read_through_official_mcp_server(): void
    {
        KitukizuriServer::actingAs($this->user())->tool($this->tool('list'))
            ->assertOk()->assertSee('Ana')->assertDontSee('special')->assertDontSee('Inactive')->assertDontSee('password');
    }
    public function test_cross_company_record_and_invalid_arguments_are_rejected(): void
    {
        $user = $this->user();
        KitukizuriServer::actingAs($user)->tool($this->tool('get'), ['id' => '2'])->assertHasErrors();
        KitukizuriServer::actingAs($user)->tool($this->tool('list'), ['limit' => 101])->assertHasErrors();
        KitukizuriServer::actingAs($user)->tool($this->tool('list'), ['tenant_id' => 2])->assertHasErrors();
    }
    public function test_schema_and_sensitive_field_filter(): void
    {
        $schema = $this->tool('create')->toArray()['inputSchema'];
        $this->assertSame(['nombre', 'fecha_nacimiento'], array_keys($schema['properties']));
        $this->assertSame('string', $schema['properties']['nombre']['type']);
        $this->assertSame(150, $schema['properties']['nombre']['maxLength']);
        $this->assertSame('date', $schema['properties']['fecha_nacimiento']['format']);
        $this->assertSame(['nombre', 'fecha_nacimiento'], $schema['required']);
    }

    public function test_administrator_discovers_all_five_tools(): void
    {
        $this->actingAs($this->user([1, 2, 3, 4]));
        $server = new KitukizuriServer(new FakeTransporter);
        $server->start();
        $this->assertSame(['pacientes.list', 'pacientes.get', 'pacientes.create', 'pacientes.update', 'pacientes.delete'],
            $server->createContext()->tools()->map->name()->values()->all());
    }

    public function test_crud_runs_through_the_official_server_and_persists_records(): void
    {
        $user = $this->user([1, 2, 3, 4]);
        KitukizuriServer::actingAs($user)->tool($this->tool('create'), ['nombre' => 'New', 'fecha_nacimiento' => '2000-01-02'])
            ->assertOk()->assertSee('New');
        $record = Patient::where('nombre', 'New')->firstOrFail();
        $this->assertSame(1, $record->empresaid);
        KitukizuriServer::actingAs($user)->tool($this->tool('update'), ['id' => (string) $record->id, 'nombre' => 'Updated', 'fecha_nacimiento' => '2000-01-02'])
            ->assertOk()->assertSee('Updated');
        $this->assertSame('Updated', $record->fresh()->nombre);
        KitukizuriServer::actingAs($user)->tool($this->tool('get'), ['id' => (string) $record->id])->assertOk()->assertSee('Updated');
        KitukizuriServer::actingAs($user)->tool($this->tool('delete'), ['id' => (string) $record->id])->assertOk();
        $this->assertNull($record->fresh());
    }

    public function test_create_does_not_grant_update_or_delete_and_revocation_is_immediate(): void
    {
        $user = $this->user([1]);
        $this->actingAs($user);
        $tool = $this->tool('create');
        $this->assertTrue($tool->shouldRegister(new \Laravel\Mcp\Request));
        $this->assertFalse($this->tool('update')->shouldRegister(new \Laravel\Mcp\Request));
        DB::table('rolModuloPermiso')->delete();
        $response = $tool->handle(new \Laravel\Mcp\Request(['nombre' => 'Revoked', 'fecha_nacimiento' => '2000-01-01']));
        $this->assertTrue($response->isError());
        $this->assertSame(3, Patient::count());
    }

    public function test_write_validation_and_cross_company_writes_fail(): void
    {
        $user = $this->user([1, 2, 3, 4]);
        KitukizuriServer::actingAs($user)->tool($this->tool('create'), ['nombre' => str_repeat('x', 151)])->assertHasErrors();
        KitukizuriServer::actingAs($user)->tool($this->tool('create'), ['nombre' => 'New', 'fecha_nacimiento' => '2000-01-01', 'empresaid' => 2])->assertHasErrors();
        KitukizuriServer::actingAs($user)->tool($this->tool('create'), ['nombre' => 'New', 'fecha_nacimiento' => '2000-01-01', 'password' => 'secret'])->assertHasErrors();
        KitukizuriServer::actingAs($user)->tool($this->tool('update'), ['id' => '2', 'nombre' => 'Changed', 'fecha_nacimiento' => '2000-01-01'])->assertHasErrors();
        KitukizuriServer::actingAs($user)->tool($this->tool('delete'), ['id' => '2'])->assertHasErrors();
        $this->assertSame('special', Patient::findOrFail(2)->nombre);
        $this->assertSame(3, Patient::count());
    }

    public function test_route_cache_and_inspection_do_not_cache_permissions(): void
    {
        $this->artisan('krud:mcp:cache')->assertSuccessful();
        $this->artisan('krud:mcp:inspect', ['module' => 'pacientes'])->assertSuccessful();
        $this->assertSame(0, PatientController::$constructions);
        $this->actingAs($this->user([]));
        $this->assertFalse($this->tool('create')->shouldRegister(new \Laravel\Mcp\Request));
        $this->artisan('krud:mcp:cache', ['--clear' => true])->assertSuccessful();
    }

    public function test_authenticated_http_discovery_and_forged_call(): void
    {
        $user = $this->user([2]);
        $this->withToken('test-token-'.$user->id)
            ->postJson('/mcp/kitukizuri', ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'tools/list'])
            ->assertOk()->assertJsonPath('result.tools.0.name', 'pacientes.list')
            ->assertJsonCount(2, 'result.tools');
        $this->withToken('test-token-'.$user->id)
            ->postJson('/mcp/kitukizuri', ['jsonrpc' => '2.0', 'id' => 2, 'method' => 'tools/call',
                'params' => ['name' => 'pacientes.create', 'arguments' => ['nombre' => 'Forged']]])
            ->assertOk()->assertJsonPath('error.code', -32602);
        $this->assertSame(3, Patient::count());
    }

    public function test_unauthenticated_http_is_rejected(): void
    {
        $this->postJson('/mcp/kitukizuri', ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'tools/list'])->assertUnauthorized();
    }

    public function test_real_tenant_middleware_isolates_hosts_and_tokens(): void
    {
        $this->user([1, 2, 3, 4]);
        $paths = [tempnam(sys_get_temp_dir(), 'krud-tenant-a-'), tempnam(sys_get_temp_dir(), 'krud-tenant-b-')];
        try {
            foreach ($paths as $path) {
                $pdo = new \PDO('sqlite:'.$path);
                foreach (DB::select("SELECT name, sql FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'") as $table) {
                    $pdo->exec($table->sql);
                    foreach (DB::table($table->name)->get() as $row) {
                        $values = (array) $row;
                        $sql = 'INSERT INTO "'.$table->name.'" ("'.implode('","', array_keys($values)).'") VALUES ('.implode(',', array_fill(0, count($values), '?')).')';
                        $pdo->prepare($sql)->execute(array_values($values));
                    }
                }
            }
            $pdo = new \PDO('sqlite:'.$paths[1]);
            $pdo->exec("UPDATE patients SET nombre = 'Tenant B' WHERE id = 1");
            $pdo->prepare('UPDATE users SET api_token = ?')->execute([hash('sha256', 'tenant-b-token')]);
            config(['database.connections.tenants' => ['driver' => 'sqlite', 'database' => ':memory:']]);
            Schema::connection('tenants')->create('tenants', function (Blueprint $t) {
                $t->increments('tenant_id');
                foreach (['dominio', 'db', 'db_host', 'db_username', 'db_password'] as $column) { $t->string($column); }
                $t->integer('activo');
            });
            foreach ($paths as $i => $path) {
                DB::connection('tenants')->table('tenants')->insert(['tenant_id' => $i + 1, 'dominio' => 'tenant-'.($i + 1).'.test',
                    'db' => $path, 'db_host' => 'localhost', 'db_username' => 'test', 'db_password' => '', 'activo' => 1]);
                $pdo = new \PDO('sqlite:'.$path);
                $pdo->exec('CREATE TABLE empresas (empresaid INTEGER PRIMARY KEY, nombre TEXT)');
            }
            // Exercise the real host resolver with separate physical databases. The
            // mysql connector alone is substituted because CI uses SQLite.
            DB::extend('mysql', fn ($config, $name) => new \Illuminate\Database\SQLiteConnection(
                new \PDO('sqlite:'.$config['database']), $config['database'], '', $config));
            config(['database.default' => 'mysql', 'kitukizuri.multiTenants' => true]);
            app(\Illuminate\Contracts\Http\Kernel::class)->pushMiddleware(\Icebearsoft\Kitukizuri\App\Http\Middleware\Tenant::class);
            $payload = ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'tools/call', 'params' => ['name' => 'pacientes.get', 'arguments' => ['id' => '1']]];
            $this->withServerVariables(['HTTP_HOST' => 'tenant-1.test'])->withToken('test-token-1')
                ->postJson('http://tenant-1.test/mcp/kitukizuri', $payload)->assertOk()->assertJsonPath('result.structuredContent.data.nombre', 'Ana');
            app('auth')->forgetGuards();
            $this->withServerVariables(['HTTP_HOST' => 'tenant-2.test'])->withToken('test-token-1')
                ->postJson('http://tenant-2.test/mcp/kitukizuri', $payload)->assertUnauthorized();
            app('auth')->forgetGuards();
            $this->withToken('tenant-b-token')->postJson('http://tenant-2.test/mcp/kitukizuri', $payload)
                ->assertOk()->assertJsonPath('result.structuredContent.data.nombre', 'Tenant B');
            app('auth')->forgetGuards();
            $this->withServerVariables(['HTTP_HOST' => 'unknown.test'])->postJson('http://unknown.test/mcp/kitukizuri', $payload)->assertNotFound();
        } finally {
            DB::purge('mysql');
            foreach ($paths as $path) { unlink($path); }
        }
    }
}
