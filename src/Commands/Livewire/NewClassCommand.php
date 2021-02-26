<?php

namespace LaravelRoad\JetGen\Commands\Livewire;

use LaravelRoad\JetGen\Commands\GeneratorCommand;
use LaravelRoad\JetGen\Parsers\SchemaParser;
use LaravelRoad\JetGen\SyntaxBuilders\Livewire\NewClassSyntaxBuilder;

class NewClassCommand extends GeneratorCommand
{
    protected $signature = 'jetgen:livewire:new-class
        {name : resource name (singular)}
        {--s|schema= : Schema options}';

    protected $description = 'Create new Livewire Component class and apply schema at the same time';

    public function compileStub(): string
    {
        $schema = (new SchemaParser())->parse($this->option('schema'));

        $content = (new NewClassSyntaxBuilder())->create($schema);

        $content = str_replace(
            ['{{modelName}}', '{{objectName}}'],
            [$this->modelName(), $this->objectName()],
            $content
        );

        return $content;
    }

    protected function path(): string
    {
        $filename = $this->fileName();

        return app_path("Http/Livewire/{$this->modelName()}/{$filename}");
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
        return sprintf('%sNew', $this->modelName());
    }
}