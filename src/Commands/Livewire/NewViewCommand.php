<?php

namespace LaravelRoad\JetGen\Commands\Livewire;

use LaravelRoad\JetGen\Commands\GeneratorCommand;
use LaravelRoad\JetGen\SyntaxBuilders\Livewire\NewViewSyntaxBuilder;

class NewViewCommand extends GeneratorCommand
{
    protected $signature = 'jetgen:livewire:new-view
        {name : resource name (singular)}
        {--s|schema= : Schema options}';

    protected $description = 'Create new Livewire Component View and apply schema at the same time';

    public function compileStub(): string
    {
        $content = (new NewViewSyntaxBuilder())->create($this->option('schema'));

        $content = str_replace('{{modelName}}', $this->modelName(), $content);

        return $content;
    }

    protected function path(): string
    {
        return resource_path("views/livewire/{$this->viewName()}/{$this->fileName()}");
    }

    protected function alreadyExists(): bool
    {
        return $this->filesystem->exists($this->path());
    }

    protected function fileName(): string
    {
        return sprintf('%s-new.blade.php', $this->viewName());
    }
}