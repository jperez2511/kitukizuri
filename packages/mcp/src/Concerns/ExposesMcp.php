<?php

namespace Icebearsoft\Kitukizuri\Mcp\Concerns;

/** Opt-in on Krud subclasses. Definitions must not depend on requests or users. */
trait ExposesMcp
{
    private array $mcpFields = [];

    protected static function mcp(): array
    {
        return ['enabled' => false, 'operations' => []];
    }

    final public static function mcpConfiguration(): array
    {
        return static::mcp();
    }

    protected function setMcpField(string $field, array $options): void
    {
        if (array_diff(array_keys($options), ['read', 'write', 'hidden', 'description'])) {
            throw new \InvalidArgumentException('Unknown MCP field option.');
        }
        $this->mcpFields[$field] = $options;
    }

    final public function mcpFieldConfiguration(): array
    {
        return $this->mcpFields;
    }
}
