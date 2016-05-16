<?php
namespace Seanstewart\UpdateScripts;

use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\ServiceProvider;

class UpdateScriptsServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        @mkdir(base_path('updates'));

        $this->publishes([
            __DIR__.'/migrations' => database_path('migrations')
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('\Seanstewart\UpdateScripts\Updater', function ($app) {
            $repository = $app['migration.repository'];
            return new Updater($repository, $app['db'], $app['files']);
        });

        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */
        $this->commands([
            \Seanstewart\UpdateScripts\UpdateMakeCommand::class,
            \Seanstewart\UpdateScripts\UpdateRunCommand::class
        ]);

    }

}
