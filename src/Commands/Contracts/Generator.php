<?php

namespace LaravelRoad\JetGen\Commands\Contracts;

interface Generator
{
    public function handle(): void;

    public function compileStub(): string;
}