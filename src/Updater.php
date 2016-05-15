<?php
namespace Seanstewart\UpdateScripts;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Arr;

class Updater extends Migrator {

    public function run($path, $pretend = false, array $options = [])
    {
        $path = $this->getPath();

        $files = $this->getMigrationFiles($path);
        // Once we grab all of the migration files for the path, we will compare them
        // against the migrations that have already been run for this package then
        // run each of the outstanding migrations against a database connection.
        $ran = $this->getRepository()->getRan();

        $updates = array_diff($files, $ran);

        $this->requireFiles($path, $updates);

        $this->runUpdateList($updates, $options);
    }

    public function runUpdateList($updates, $options)
    {
        // First we will just make sure that there are any migrations to run. If there
        // aren't, we will just make a note of it to the developer so they're aware
        // that all of the migrations have been run against this database system.
        if (count($updates) == 0)
        {
            $this->note('<info>No updates to run.</info>');

            return;
        }

        $batch = $this->getRepository()->getNextBatchNumber();

        $step = Arr::get($options, 'step', false);

        foreach ($updates as $file)
        {
            $this->runUpdate($file, $batch);

            // If we are stepping through the migrations, then we will increment the
            // batch value for each individual migration that is run. That way we
            // can run "artisan migrate:rollback" and undo them one at a time.
            if ($step)
            {
                $batch ++;
            }
        }
    }

    public function runUpdate($file, $batch)
    {
        $update = $this->resolve($file);

        // Run the update
        $update->run();

        // Run the after method
        $update->after();

        // Once we have run a migrations class, we will log that it was run in this
        // repository so that we don't try to run it next time we do a migration
        // in the application. A migration repository keeps the migrate order.

        $this->getRepository()->log($file, $batch, $update->details);

        $this->note("<info>Updated:</info> $file");
    }

    public function getPath()
    {
        return base_path('updates');
    }

    public function getRepository()
    {
        return new UpdateRepository($this->resolver, 'updates');
    }

    public function resolve($file)
    {
        return parent::resolve($file);
    }


}