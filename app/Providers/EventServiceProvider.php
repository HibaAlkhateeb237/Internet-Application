<?php

namespace App\Providers;

use App\Listeners\BackupFailListener;
use App\Listeners\BackupSuccessListener;
use Illuminate\Support\ServiceProvider;
use Spatie\Backup\Events\BackupHasFailed;
use Spatie\Backup\Events\BackupWasSuccessful;


class EventServiceProvider extends ServiceProvider
{


    protected $listen = [
        BackupWasSuccessful::class => [
            BackupSuccessListener::class,
        ],

        BackupHasFailed::class => [
            BackupFailListener::class,
        ],
    ];





    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
