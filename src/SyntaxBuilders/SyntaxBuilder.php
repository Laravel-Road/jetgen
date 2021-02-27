<?php

namespace LaravelRoad\JetGen\SyntaxBuilders;

use LaravelRoad\JetGen\Parsers\SchemaParser;

abstract class SyntaxBuilder
{
    protected array $template;
    protected array $schema;

    abstract protected function constructSchema(): array;

    abstract protected function into(string $wrapper): string;

    abstract protected function getSchemaWrapper(): string;

    public function create(string $schema): string
    {
        $this->schema = (new SchemaParser())->parse($schema);
        return $this->createSchema();
    }

    protected function createSchema(): string
    {
        $fields = $this->constructSchema();

        return $this->insert($fields)->into($this->getSchemaWrapper());
    }

    protected function insert(array $template): SyntaxBuilder
    {
        $this->template = $template;

        return $this;
    }

    protected function rejectForeign()
    {
        return array_filter($this->schema, fn($field) => ! array_key_exists('on', $field['options']));
    }

    protected function filterForeign()
    {
        return array_filter($this->schema, fn($field) => $field['foreign']);
    }
}
