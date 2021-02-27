<?php

namespace LaravelRoad\JetGen\SyntaxBuilders\Livewire;

use LaravelRoad\JetGen\SyntaxBuilders\SyntaxBuilder;

class NewClassSyntaxBuilder extends SyntaxBuilder
{
    protected function into(string $wrapper): string
    {
        return str_replace(['{{column}}'], $this->template, $wrapper);
    }

    protected function getSchemaWrapper(): string
    {
        return file_get_contents(__DIR__ . '/../../../stubs/app/Http/Livewire/New.php.stub');
    }

    protected function constructSchema(array $schema): array
    {
        $fields = array_map(
            fn ($field) => $this->addColumn($field),
            array_filter($schema, fn($field) => ! array_key_exists('on', $field['options']))
        );

        $template['column'] = implode("\n".str_repeat(' ', 12), $fields);

        return $template;
    }

    private function addColumn(array $field): string
    {
        return sprintf("'new{{modelName}}.%s' => '%s',", $field['name'], $this->getRule($field));
    }

    private function getRule(array $field): string
    {
        $type = '';

        if (array_key_exists('nullable', $field['options'])) {
            $type .= 'nullable|';
        }

        if (!array_key_exists('nullable', $field['options'])) {
            $type .= 'required|';
        }

        if (in_array($field['type'], config('jetgen.string_types'))) {
            $type .= 'string|';

            if ($field['arguments']) {
                $type .= 'max:'.$field['arguments'][0].'|';
            }
        }

        $integerTypes = config('jetgen.integer_types');
        array_push($integerTypes, 'foreignId');
        if (in_array($field['type'], $integerTypes)) {
            $type .= 'integer|';
        }

        if (in_array($field['type'], config('jetgen.float_types'))) {
            $type .= 'numeric|';
        }

        return rtrim($type, '|');
    }
}
