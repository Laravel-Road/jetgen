<?php

namespace LaravelRoad\JetGen\Tests\Unit\Livewire;

use LaravelRoad\JetGen\Commands\Livewire\NewViewCommand;
use LaravelRoad\JetGen\Tests\TestCase;
use Symfony\Component\Console\Input\ArrayInput;

class NewViewCommandTest extends TestCase
{
    /**
     * @test
     */
    public function checkAlreadyExistsNewViewCommand()
    {
        $path = resource_path("views/livewire/post/post-new.blade.php");

        $dirname = dirname($path);
        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }

        fopen($path, 'w');

        $this
            ->artisan('jetgen:livewire:new-view', [
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
    public function canRunNewViewCommand()
    {
        $filename = 'post-new.blade.php';
        $path = resource_path("views/livewire/post/{$filename}");

        $this
            ->artisan('jetgen:livewire:new-view', [
                'name' => 'post',
                '--schema' => 'title:string(150), subtitle:string:nullable, content:text'
            ])
            ->expectsOutput("Creating File: {$filename}")
            ->expectsOutput("Created File: {$filename}")
            ->assertExitCode(0);
    }

    /**
     * @test
     */
    public function checkCompiledStubNewView()
    {
        /** @var NewViewCommand $newViewCommand */
        $newViewCommand = $this->app->make(NewViewCommand::class);

        $parameters = [
            'jetgen:livewire:new-view',
            'name' => 'post',
            '--schema' => 'title:string(150), subtitle:string:nullable, content:text, user_id:foreignId:constrained'
        ];

        $input = new ArrayInput($parameters, $newViewCommand->getDefinition());
        $newViewCommand->setInput($input);

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../../../storage/tests/resources/views/livewire/post/post-new.compiled'),
            $newViewCommand->compileStub()
        );
    }
}