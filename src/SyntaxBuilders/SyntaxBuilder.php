<?php

namespace LaravelRoad\JetGen\SyntaxBuilders;

abstract class SyntaxBuilder
{
    protected array $template;

    abstract protected function constructSchema(array $schema): array;

    abstract protected function into(string $wrapper): string;

    abstract protected function getSchemaWrapper(): string;

    public function create(array $schema): string
    {
        return $this->createSchema($schema);
    }

    protected function createSchema(array $schema): string
    {
        $fields = $this->constructSchema($schema);

        return $this->insert($fields)->into($this->getSchemaWrapper());
    }

    protected function insert(array $template): SyntaxBuilder
    {
        $this->template = $template;

        return $this;
    }

    protected function rejectForeign(array $schema)
    {
        return array_filter($schema, fn($field) => ! array_key_exists('on', $field['options']));
    }

    protected function filterForeign(array $schema)
    {
        array_filter($schema, fn($field) => $field['foreign']);
    }
}
