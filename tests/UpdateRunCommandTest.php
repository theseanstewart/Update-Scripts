<?php

use Mockery as m;
use Illuminate\Foundation\Application;

class UpdateRunCommandTest extends PHPUnit_Framework_TestCase{

    public function tearDown()
    {
        m::close();
    }

    public function testBasicMigrationsCallMigratorWithProperArguments()
    {
        $command = new \Seanstewart\UpdateScripts\UpdateRunCommand($updater = m::mock('Seanstewart\UpdateScripts\Updater'));
        $app = new ApplicationDatabaseMigrationStub(['path.database' => __DIR__]);
        $command->setLaravel($app);
        $updater->shouldReceive('run')->once();
        $this->runCommand($command);
    }

    protected function runCommand($command, $input = [])
    {
        return $command->run(new Symfony\Component\Console\Input\ArrayInput($input), new Symfony\Component\Console\Output\NullOutput);
    }
}

class ApplicationDatabaseMigrationStub extends Application
{
    public function __construct(array $data = [])
    {
        foreach ($data as $abstract => $instance) {
            $this->instance($abstract, $instance);
        }
    }
    public function environment()
    {
        return 'development';
    }
}