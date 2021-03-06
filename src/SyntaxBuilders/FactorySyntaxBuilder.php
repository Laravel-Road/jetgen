<?php

namespace LaravelRoad\JetGen\SyntaxBuilders;

use Illuminate\Support\Str;

class FactorySyntaxBuilder extends SyntaxBuilder
{
    protected function into(string $wrapper): string
    {
        return str_replace(['{{column}}'], $this->template, $wrapper);
    }

    protected function getSchemaWrapper(): string
    {
        return file_get_contents(__DIR__ . '/../../stubs/database/factories/factory.php.stub');
    }

    protected function constructSchema(): array
    {
        $fields = array_map(
            fn ($field) => $this->addColumn($field),
            $this->rejectForeign()
        );

        $template['column'] = implode("\n".str_repeat(' ', 12), $fields);

        return $template;
    }

    private function addColumn(array $field): string
    {
        if ('_id' === substr($field['name'], -3)) {
            return sprintf(
                "'%s' => %s::factory(),",
                $field['name'],
                Str::of(str_replace('_id', '', $field['name']))->studly()->singular()
            );
        }

        return sprintf("'%s' => \$this->faker->%s,", $field['name'], $this->fakerType($field));
    }

    private function fakerType(array $field)
    {
        $type = 'text(';

        if (in_array($field['type'], config('jetgen.integer_types'))) {
            $type = 'randomNumber(';
        }

        if (in_array($field['type'], config('jetgen.date_types'))) {
            $type = 'date(';
        }

        if ('boolean' === $field['type']) {
            $type = 'boolean(';
        }

        if ($field['arguments']) {
            $type .= $field['arguments'][0];
        }

        return $type.')';
    }
}
