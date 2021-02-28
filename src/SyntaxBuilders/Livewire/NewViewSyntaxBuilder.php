<?php

namespace LaravelRoad\JetGen\SyntaxBuilders\Livewire;

use LaravelRoad\JetGen\SyntaxBuilders\SyntaxBuilder;

class NewViewSyntaxBuilder extends SyntaxBuilder
{
    protected function into(string $wrapper): string
    {
        return str_replace(['{{column}}'], $this->template, $wrapper);
    }

    protected function getSchemaWrapper(): string
    {
        return file_get_contents(__DIR__ . '/../../../stubs/resources/views/livewire/new.blade.php.stub');
    }

    protected function constructSchema(): array
    {
        $fields = array_map(
            fn ($field) => $this->addColumn($field),
            $this->rejectForeign()
        );

        $template['column'] = implode("\n", $fields);

        return $template;
    }

    private function addColumn(array $field): string
    {
        return str_replace('%s', $field['name'], $this->getElement($field));
    }

    private function getElement(array $field): string
    {
        $element = 'input';

        if ('text' === $field['type']) {
            $element = 'textarea';
        }

        return file_get_contents(__DIR__ . "/../../../stubs/resources/views/elements/livewire/{$element}.stub");
    }
}
