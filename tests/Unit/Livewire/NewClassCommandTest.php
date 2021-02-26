<?php

namespace LaravelRoad\JetGen\Tests\Unit\Livewire;

use LaravelRoad\JetGen\Commands\Livewire\NewClassCommand;
use LaravelRoad\JetGen\Tests\TestCase;
use Symfony\Component\Console\Input\ArrayInput;

class NewClassCommandTest extends TestCase
{
    /**
     * @test
     */
    public function checkAlreadyExistsNewClassCommand()
    {
        $path = app_path("Http/Livewire/Post/PostNew.php");

        $dirname = dirname($path);
        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }

        fopen($path, 'w');

        $this
            ->artisan('jetgen:livewire:new-class', [
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
    public function canRunNewClassCommand()
    {
        $filename = 'PostNew.php';
        $path = app_path("Http/Livewire/Post/{$filename}");

        $this
            ->artisan('jetgen:livewire:new-class', [
                'name' => 'post',
                '--schema' => 'title:string(150), subtitle:string:nullable, content:text'
            ])
            ->expectsOutput("Creating File: {$filename}")
            ->expectsOutput("Created File: {$filename}")
            ->assertExitCode(0);

        unlink($path);
    }

    /**
     * @test
     */
    public function checkCompiledStubNewClass()
    {
        /** @var NewClassCommand $newClassCommand */
        $newClassCommand = $this->app->make(NewClassCommand::class);

        $parameters = [
            'jetgen:factory',
            'name' => 'post',
            '--schema' => 'title:string(150), subtitle:string:nullable, content:text, user_id:foreignId:constrained'
        ];

        $input = new ArrayInput($parameters, $newClassCommand->getDefinition());
        $newClassCommand->setInput($input);

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../../../storage/tests/app/Http/Livewire/Post/PostNew.compiled'),
            $newClassCommand->compileStub()
        );
    }
}