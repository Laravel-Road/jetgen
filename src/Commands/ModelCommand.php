<?php

namespace LaravelRoad\JetGen\Commands;

use LaravelRoad\JetGen\Parsers\SchemaParser;
use LaravelRoad\JetGen\SyntaxBuilders\ModelSyntaxBuilder;

class ModelCommand extends GeneratorCommand
{
    protected $signature = 'jetgen:model
        {name : resource name (singular)}
        {--s|schema= : Schema options}';

    protected $description = 'Create new model class and apply schema at the same time';

    public function compileStub(): string
    {
        $schema = (new SchemaParser())->parse($this->option('schema'));

        $content = (new ModelSyntaxBuilder())->create($schema);

        $content = str_replace('{{modelName}}', $this->modelName(), $content);

        return $content;
    }

    protected function path(): string
    {
        $filename = $this->fileName();

        return app_path("Models/{$filename}");
    }

    protected function alreadyExists(): bool
    {
        return $this->filesystem->exists($this->path());
    }

    protected function fileName(): string
    {
        return $filename = sprintf('%s.php', $this->modelName());
    }
}