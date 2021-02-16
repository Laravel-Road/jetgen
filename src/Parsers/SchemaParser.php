<?php

namespace LaravelRoad\JetGen\Parsers;

use Illuminate\Support\Str;

class SchemaParser
{
    /**
     * @var array
     */
    private array $schema = [];

    /**
     * @param string $schema
     * @return array
     */
    public function parse(string $schema): array
    {
        $fields = $this->splitIntoFields($schema);

        foreach ($fields as $field) {
            $segments = $this->parseSegments($field);

            if ($this->fieldNeedsForeignConstraint($segments)) {
                unset($segments['options']['foreign']);

                $this->addField($segments);

                $this->addForeignConstraint($segments);

                continue;
            }

            $this->addField($segments);
        }

        return $this->schema;
    }

    /**
     * @param array $field
     * @return SchemaParser
     */
    private function addField(array $field): SchemaParser
    {
        $this->schema[] = $field;

        return $this;
    }

    /**
     * @param string $schema
     * @return array
     */
    private function splitIntoFields(string $schema): array
    {
        return preg_split('/,\s?(?![^()]*\))/', $schema);
    }

    /**
     * @param string $field
     * @return array
     */
    private function parseSegments(string $field): array
    {
        $segments = explode(':', $field);

        $name = array_shift($segments);
        $type = array_shift($segments);

        $arguments = [];
        $options = $this->parseOptions($segments);

        if (preg_match('/(.+?)\(([^)]+)\)/', $type, $matches)) {
            $type = $matches[1];
            $arguments = explode(',', $matches[2]);
        }

        if (! in_array($type, config('jetgen.blueprint_types'))) {
            throw new \InvalidArgumentException("Column Type: $type not available");
        }

        return compact('name', 'type', 'arguments', 'options');
    }

    /**
     * @param array $options
     * @return array
     */
    private function parseOptions(array $options): array
    {
        if (empty($options)) {
            return [];
        }

        $results = [];
        foreach ($options as $option) {
            if (Str::contains($option, '(')) {
                preg_match('/([a-z]+)\(([^\)]+)\)/i', $option, $matches);

                $results[$matches[1]] = $matches[2];
            } else {
                $results[$option] = true;
            }
        }

        return $results;
    }

    /**
     * @param array $segments
     */
    private function addForeignConstraint(array $segments): void
    {
        $string = sprintf(
            "%s:foreign:references('id'):on('%s')",
            $segments['name'],
            $this->getTableNameFromForeignKey($segments['name'])
        );

        $this->addField($this->parseSegments($string));
    }

    /**
     * @param string $key
     * @return string
     */
    private function getTableNameFromForeignKey(string $key): string
    {
        return Str::plural(str_replace('_id', '', $key));
    }

    /**
     * @param array $segments
     * @return bool
     */
    private function fieldNeedsForeignConstraint(array $segments): bool
    {
        return array_key_exists('foreign', $segments['options']);
    }
}
