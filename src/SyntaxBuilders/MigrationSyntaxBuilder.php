<?php

namespace LaravelRoad\JetGen\SyntaxBuilders;

class MigrationSyntaxBuilder extends SyntaxBuilder
{
    protected function into(string $wrapper): string
    {
        return str_replace(['{{column}}'], $this->template, $wrapper);
    }

    protected function getSchemaWrapper(): string
    {
        return file_get_contents(__DIR__ . '/../../stubs/database/migrations/migration.php.stub');
    }

    protected function constructSchema(): array
    {
        $fields = array_map(fn ($field) => $this->addColumn($field), $this->schema);

        $template['column'] = implode("\n".str_repeat(' ', 12), $fields);

        return $template;
    }

    private function addColumn(array $field): string
    {
        $syntax = sprintf("\$table->%s('%s')", $field['type'], $field['name']);

        // If there are arguments for the schema type, like decimal('amount', 5, 2)
        // then we have to remember to work those in.
        if ($field['arguments']) {
            $syntax = substr($syntax, 0, -1).', ';

            $syntax .= implode(', ', $field['arguments']).')';
        }

        foreach ($field['options'] as $method => $value) {
            $syntax .= sprintf('->%s(%s)', $method, $value === true ? '' : $value);
        }

        return $syntax . ';';
    }
}
