<?php

namespace LaravelRoad\JetGen\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use LaravelRoad\JetGen\Commands\Contracts\Generator;
use LaravelRoad\JetGen\Commands\Traits\NamingConvention;

abstract class GeneratorCommand extends Command implements Generator
{
    use NamingConvention;

    protected Filesystem $filesystem;

    protected string $existsMessage = 'Already Exists!';

    abstract protected function path(): string;

    abstract protected function alreadyExists(): bool;

    abstract protected function fileName(): string;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle(): void
    {
        $this->line("Creating File: {$this->fileName()}");

        if ($this->alreadyExists()) {
            $this->warn($this->existsMessage);
            return;
        }

        $this->generate();

        $this->line("<info>Created File:</info> {$this->fileName()}");
    }

    protected function generate()
    {
        $this->makeDirectory();

        $this->filesystem->put($this->path(), $this->compileStub());
    }

    protected function makeDirectory()
    {
        if (! $this->filesystem->isDirectory(dirname($this->path()))) {
            $this->filesystem->makeDirectory(dirname($this->path()), 0755, true);
        }
    }
}