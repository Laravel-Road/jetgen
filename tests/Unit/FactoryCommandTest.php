<?php

namespace LaravelRoad\JetGen\Tests\Unit;

use LaravelRoad\JetGen\Commands\FactoryCommand;
use LaravelRoad\JetGen\Tests\TestCase;
use Symfony\Component\Console\Input\ArrayInput;

class FactoryCommandTest extends TestCase
{
    /**
     * @test
     */
    public function checkAlreadyExistsFactoryCommand()
    {
        $classname = 'PostFactory';
        $filename = $classname.'.php';
        $path = database_path("factories/{$filename}");

        fopen($path, 'w');

        $this
            ->artisan('jetgen:factory', [
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
    public function canRunFactoryCommand()
    {
        $classname = 'PostFactory';
        $filename = $classname.'.php';

        $this
            ->artisan('jetgen:factory', [
                'name' => 'post',
                '--schema' => 'title:string(150), subtitle:string:nullable, content:text'
            ])
            ->expectsOutput("Creating File: {$filename}")
            ->expectsOutput("Created File: {$filename}")
            ->assertExitCode(0);

        unlink(database_path("factories/{$filename}"));
    }

    /**
     * @test
     */
    public function checkCompiledStubFactory()
    {
        /** @var FactoryCommand $factoryCommnad */
        $factoryCommand = $this->app->make(FactoryCommand::class);

        $parameters = [
            'jetgen:factory',
            'name' => 'post',
            '--schema' => 'title:string(150), subtitle:string:nullable, content:text, user_id:foreignId:constrained'
        ];

        $input = new ArrayInput($parameters, $factoryCommand->getDefinition());
        $factoryCommand->setInput($input);

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../../storage/tests/database/factories/PostFactory.compiled'),
            $factoryCommand->compileStub()
        );
    }

    /**
     * @test
     */
    public function checkCompiledStubOldForeignSyntaxFactory()
    {
        /** @var FactoryCommand $factoryCommnad */
        $factoryCommand = $this->app->make(FactoryCommand::class);

        $parameters = [
            'jetgen:factory',
            'name' => 'post',
            '--schema' => 'title:string(150), subtitle:string:nullable, content:text, user_id:unsignedBigInteger:foreign'
        ];

        $input = new ArrayInput($parameters, $factoryCommand->getDefinition());
        $factoryCommand->setInput($input);

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../../storage/tests/database/factories/PostFactory.compiled'),
            $factoryCommand->compileStub()
        );
    }
}