<?php
/**
 * Created by PhpStorm.
 * User: seanstewart
 * Date: 5/15/16
 * Time: 7:55 AM
 */

namespace Seanstewart\UpdateScripts;


use Illuminate\Database\Console\Migrations\MigrateMakeCommand;

class BaseCommand extends MigrateMakeCommand{

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return $this->laravel->basePath().'/updates';
    }

}