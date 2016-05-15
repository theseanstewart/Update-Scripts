<?php
namespace Seanstewart\UpdateScripts;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Str;

class UpdateCreator extends MigrationCreator{

    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function getStubPath()
    {
        return __DIR__.'/stubs';
    }

}