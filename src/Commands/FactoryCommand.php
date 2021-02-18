<?php

namespace LaravelRoad\JetGen\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use LaravelRoad\JetGen\Parsers\SchemaParser;
use LaravelRoad\JetGen\SyntaxBuilders\FactorySyntaxBuilder;

class FactoryCommand extends Command
{
    protected $signature = 'jetgen:factory
        {name : resource name (singular)}
        {--s|schema= : Schema options}';

    protected $description = 'Create new factory class and apply schema at the same time';

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle(): void
    {
        if ($this->alreadyExists()) {
            $this->warn('Existe factory com o mesmo nome.');
            return;
        }

        $this->generate();

        $this->line("<info>Created {$this->className()}:</info> {$this->fileName()}");

    }

    public function generate()
    {
        $this->makeDirectory();

        $this->filesystem->put($this->path(), $this->compileStub());
    }

    public function compileStub()
    {
        $schema = (new SchemaParser())->parse($this->option('schema'));

        $content = (new FactorySyntaxBuilder())->create($schema);

        $content = str_replace('{{modelName}}', $this->modelName(), $content);

        return $content;
    }

    private function path(): string
    {
        $filename = $this->fileName();

        return database_path("factories/{$filename}");
    }

    private function alreadyExists(): bool
    {
        return $this->filesystem->exists(database_path("factories/{$this->fileName()}"));
    }

    private function makeDirectory()
    {
        if (! $this->filesystem->isDirectory(dirname($this->path()))) {
            $this->filesystem->makeDirectory(dirname($this->path()), 0755, true);
        }
    }

    private function fileName(): string
    {
        return $filename = sprintf('%s.php', $this->className());
    }

    private function resourceName()
    {
        return Str::of($this->argument('name'))
            ->lower()
            ->singular();
    }

    private function tableName()
    {
        return Str::of($this->argument('name'))
            ->snake()
            ->plural();
    }

    private function modelName()
    {
        return $this
            ->resourceName()
            ->studly();
    }

    private function controllerName()
    {
        return sprintf('%sController', $this->modelName());
    }

    private function routeName()
    {
        return $this
            ->tableName()
            ->kebab();
    }

    private function paramName()
    {
        return $this
            ->modelName()
            ->kebab();
    }

    private function className()
    {
        return sprintf('%sFactory', $this->modelName());
    }
}