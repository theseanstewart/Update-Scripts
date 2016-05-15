<?php
namespace Seanstewart\UpdateScripts;


use Carbon\Carbon;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class UpdateRepository extends DatabaseMigrationRepository {

    /**
     * Log that a migration was run.
     *
     * @param  string $file
     * @param  int $batch
     * @param string $details
     */
    public function log($file, $batch, $details = '')
    {
        $record = [
            'migration'  => $file,
            'batch'      => $batch,
            'details'    => $details,
            'created_at' => Carbon::now()
        ];

        $this->table()->insert($record);
    }

}