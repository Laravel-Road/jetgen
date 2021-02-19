<?php

namespace LaravelRoad\JetGen\Tests\Unit;

use Illuminate\Support\Str;
use LaravelRoad\JetGen\Commands\MigrationCommand;
use LaravelRoad\JetGen\Tests\TestCase;
use Symfony\Component\Console\Input\ArrayInput;

class MigrationCommandTest extends TestCase
{
    /**
     * @test
     */
    public function canRunMigrationCommand()
    {
        $filename = sprintf(
            '%s_create_%s_table.php',
            now()->format('Y_H_d_His'),
            Str::snake($name = 'posts'),
        );

        $this
            ->artisan('jetgen:migration', [
                'name' => Str::singular($name),
                '--schema' => 'title:string(150), subtitle:string:nullable, content:text'
            ])
            ->expectsOutput("Creating File: {$filename}")
            ->expectsOutput("Created File: {$filename}")
            ->assertExitCode(0);

        unlink(database_path("migrations/{$filename}"));
    }

    /**
     * @test
     */
    public function checkCompiledStubMigration()
    {
        /** @var MigrationCommand $migrationCommnad */
        $migrationCommand = $this->app->make(MigrationCommand::class);

        $parameters = [
            'jetgen:migration',
            'name' => 'post',
            '--schema' => 'title:string(150), subtitle:string:nullable, content:text'
        ];

        $input = new ArrayInput($parameters, $migrationCommand->getDefinition());
        $migrationCommand->setInput($input);

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../../storage/tests/database/migrations/create_posts_table.compiled'),
            $migrationCommand->compileStub()
        );
    }
}