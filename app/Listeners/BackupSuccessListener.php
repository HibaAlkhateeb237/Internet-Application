<?php

namespace App\Listeners;

use App\Models\BackupLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Backup\Events\BackupWasSuccessful;

class BackupSuccessListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BackupWasSuccessful $event): void
    {

        BackupLog::create([
            'success' => true,
            'message' => 'Backup completed successfully',

        ]);


    }
}
