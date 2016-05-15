<?php
namespace Seanstewart\UpdateScripts;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class UpdateRunCommand extends Command {

    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run update files';

    /**
     * The update creator instance.
     *
     * @var \Seanstewart\UpdateScripts\Updater
     */
    protected $updater;

    /**
     * Create a new migration command instance.
     *
     * @param Updater $updater
     */
    public function __construct(Updater $updater)
    {
        parent::__construct();

        $this->updater = $updater;
    }


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (! $this->confirmToProceed()) {
            return;
        }

        $this->updater->run('');

        // Once the migrator has run we will grab the note output and send it out to
        // the console screen, since the migrator itself functions without having
        // any instances of the OutputInterface contract passed into the class.
        foreach ($this->updater->getNotes() as $note) {
            $this->output->writeln($note);
        }
    }

}
