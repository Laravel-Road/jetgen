<?php

namespace LaravelRoad\JetGen\Commands;

use LaravelRoad\JetGen\SyntaxBuilders\FactorySyntaxBuilder;

class FactoryCommand extends GeneratorCommand
{
    protected $signature = 'jetgen:factory
        {name : resource name (singular)}
        {--s|schema= : Schema options}';

    protected $description = 'Create new factory class and apply schema at the same time';

    public function compileStub(): string
    {
        $content = (new FactorySyntaxBuilder())->create($this->option('schema'));

        $content = str_replace('{{modelName}}', $this->modelName(), $content);

        return $content;
    }

    protected function path(): string
    {
        $filename = $this->fileName();

        return database_path("factories/{$filename}");
    }

    protected function alreadyExists(): bool
    {
        return $this->filesystem->exists($this->path());
    }

    protected function fileName(): string
    {
        return $filename = sprintf('%s.php', $this->className());
    }

    private function className(): string
    {
        return sprintf('%sFactory', $this->modelName());
    }
}