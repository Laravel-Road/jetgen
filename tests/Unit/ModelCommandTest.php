<?php

namespace LaravelRoad\JetGen\Tests\Unit;

use LaravelRoad\JetGen\Commands\ModelCommand;
use LaravelRoad\JetGen\Tests\TestCase;
use Symfony\Component\Console\Input\ArrayInput;

class ModelCommandTest extends TestCase
{
    /**
     * @test
     */
    public function checkAlreadyExistsModelCommand()
    {
        $classname = 'Post';
        $filename = $classname.'.php';
        $path = app_path("Models/{$filename}");

        fopen($path, 'w');

        $this
            ->artisan('jetgen:model', [
                'name' => 'post',
                '--schema' => 'title:string(150), subtitle:string:nullable, content:text'
            ])
            ->expectsOutput('Already Exists!')
            ->assertExitCode(0);

        unlink($path);
    }

    /**
     * @test
     */
    public function canRunModelCommand()
    {
        $classname = 'Post';
        $filename = $classname.'.php';

        $this
            ->artisan('jetgen:model', [
                'name' => 'post',
                '--schema' => 'title:string(150), subtitle:string:nullable, content:text'
            ])
            ->expectsOutput("Creating File: {$filename}")
            ->expectsOutput("Created File: {$filename}")
            ->assertExitCode(0);

        unlink(app_path("Models/{$filename}"));
    }

    /**
     * @test
     */
    public function checkCompiledStubModel()
    {
        /** @var ModelCommand $modelCommnad */
        $modelCommand = $this->app->make(ModelCommand::class);

        $parameters = [
            'jetgen:model',
            'name' => 'post',
            '--schema' => 'title:string(150), subtitle:string:nullable, content:text'
        ];

        $input = new ArrayInput($parameters, $modelCommand->getDefinition());
        $modelCommand->setInput($input);

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../../storage/tests/app/Models/Post.compiled'),
            $modelCommand->compileStub()
        );
    }
}