<?php

namespace App\Listeners;

use App\Models\BackupLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Backup\Events\BackupHasFailed;

class BackupFailListener
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
    public function handle(BackupHasFailed $event): void
    {


        BackupLog::create([
            'success' => false,
            'message' => $event->exception->getMessage(),

        ]);





    }
}
