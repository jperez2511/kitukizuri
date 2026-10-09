<?php

namespace Icebearsoft\Kitukizuri\Mcp\Metadata;

class FieldPolicy
{
    public function sensitive(string $name): bool
    {
        $name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
        foreach (['password', 'passwd', 'token', 'secret', 'apikey', 'privatekey', 'session', 'twofactor', 'recoverycode'] as $part) {
            if (str_contains($name, $part)) {
                return true;
            }
        }
        return in_array($name, ['createdat', 'updatedat', 'deletedat', 'createdby', 'updatedby', 'deletedby',
            'empresaid', 'idempresa', 'companyid', 'tenantid', 'idtenant'], true);
    }

    public function fields(array $state, array $options): array
    {
        $model = $state['model'];
        $fields = [];
        foreach ($state['fields'] as $field) {
            $column = $field['campo'] ?? null;
            // Expressions, joined columns, files and nested tables need a domain adapter.
            if (!is_string($column) || !preg_match('/^(?:'.preg_quote($model->getTable(), '/').'\.)?([a-zA-Z_][a-zA-Z0-9_]*)$/D', $column, $match)) {
                continue;
            }
            $name = $match[1];
            $meta = $options[$name] ?? [];
            if ($this->sensitive($name) || in_array($name, $model->getHidden(), true)
                || ($meta['hidden'] ?? false) || $name === $model->getKeyName()
                || !in_array($field['tipo'], ['string', 'text', 'textarea', 'numeric', 'bool', 'date', 'datetime', 'combobox', 'select2', 'enum', 'url'], true)) {
                continue;
            }
            // No opt-in means no exposure, regardless of web visibility.
            $field['mcp'] = array_replace(['read' => false, 'write' => false], $meta);
            $field['mcp']['write'] = $field['mcp']['write'] === true && ($field['edit'] ?? true) === true;
            $field['name'] = $name;
            $field['validation'] = $state['validation'][$field['inputName']] ?? $field['validation'] ?? [];
            if ($field['mcp']['read'] === true || $field['mcp']['write'] === true) {
                if (isset($fields[$name])) {
                    throw new \LogicException('Duplicate MCP field.');
                }
                $fields[$name] = $field;
            }
        }
        return $fields;
    }
}
