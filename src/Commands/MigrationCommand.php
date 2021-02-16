<?php

namespace LaravelRoad\JetGen\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MigrationCommand extends Command
{
    protected $signature = 'jetgen:migration
        {name : resource name (singular)}
        {--s|schema= : Schema options}';

    protected $description = 'Create new migration class and apply schema at the same time';

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle(): void
    {
        // verificar se arquivo nao existe
        if ($this->alreadyExists()) {
            $this->warn('Existem migration com o mesmo nome de classe');
            return;
        }

        // gerar o arquivo
        $this->generate();

        // exibir saida
        $this->line("<info>Created {$this->className()}:</info> {$this->fileName()}");

    }

    public function generate()
    {
        // criar diretorio se nao existir
        $this->makeDirectory();

        // salvar o arquivo
        $this->filesystem->put($this->path(), $this->compileStub());
    }

    public function compileStub()
    {
        $content = $this
            ->filesystem
            ->get(__DIR__ . '/../../stubs/database/migrations/migration.php.stub');

        $content = str_replace(
            ['{{tableName}}', '{{className}}'],
            [$this->tableName(), $this->className()],
            $content
        );

        return $content;
    }

    private function path(): string
    {
        $filename = $this->fileName();

        return database_path("migrations/{$filename}");
    }

    private function alreadyExists(): bool
    {
        $files = $this->filesystem->glob(database_path('migrations/*.php'));

        foreach ($files as $file) {
            $this->filesystem->requireOnce($file);
        }

        return class_exists($this->className());
    }

    private function makeDirectory()
    {
        if (! $this->filesystem->isDirectory(dirname($this->path()))) {
            $this->filesystem->makeDirectory(dirname($this->path()), 0755, true);
        }
    }

    private function fileName(): string
    {
        return $filename = sprintf(
            '%s_create_%s_table',
            now()->format('Y_H_d_His'),
            $this->tableName(),
        );
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
        return sprintf('Create%sTable', $this->tableName()->studly());
    }
}