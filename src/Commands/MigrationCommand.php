<?php

namespace LaravelRoad\JetGen\Commands;

use LaravelRoad\JetGen\SyntaxBuilders\MigrationSyntaxBuilder;

class MigrationCommand extends GeneratorCommand
{
    protected $signature = 'jetgen:migration
        {name : resource name (singular)}
        {--s|schema= : Schema options}';

    protected $description = 'Create new migration class and apply schema at the same time';

    public function compileStub(): string
    {
        $content = (new MigrationSyntaxBuilder())->create($this->option('schema'));

        $content = str_replace(
            ['{{tableName}}', '{{className}}'],
            [$this->tableName(), $this->className()],
            $content
        );

        return $content;
    }

    protected function path(): string
    {
        $filename = $this->fileName();

        return database_path("migrations/{$filename}");
    }

    protected function alreadyExists(): bool
    {
        $files = $this->filesystem->glob(database_path('migrations/*.php'));

        foreach ($files as $file) {
            $this->filesystem->requireOnce($file);
        }

        return class_exists($this->className());
    }

    protected function fileName(): string
    {
        return $filename = sprintf(
            '%s_create_%s_table.php',
            now()->format('Y_H_d_His'),
            $this->tableName(),
        );
    }

    private function className(): string
    {
        return sprintf('Create%sTable', $this->tableName()->studly());
    }
}