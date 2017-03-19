<?php
namespace Seanstewart\UpdateScripts;

use Illuminate\Console\Command;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Support\Composer;

class UpdateMakeCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:make {name : The name of the update.}
        {--create= : The table to be created.}
        {--table= : The table to migrate.}
        {--path= : The location where the migration file should be created.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new update script file';

    /**
     * The update creator instance.
     *
     * @var \Seanstewart\UpdateScripts\UpdateCreator
     */
    protected $creator;

    /**
     * Create a new command instance.
     *
     * @param UpdateCreator $creator
     */
    public function __construct(UpdateCreator $creator, Composer $composer)
    {
        parent::__construct($creator, $composer);
    }

    /**
     * Write the migration file to disk.
     *
     * @param  string  $name
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    protected function writeMigration($name, $table, $create)
    {
        $path = $this->getMigrationPath();
        $file = pathinfo($this->creator->create($name, $path, $table, $create), PATHINFO_FILENAME);
        $this->line("<info>Created Update Script:</info> $file");
    }

}
