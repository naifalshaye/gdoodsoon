<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Path to the log directory
        $logDirectory = storage_path('logs');

        // Check if the directory exists
        if (is_dir($logDirectory)) {
            // Set the permissions to 755
            chmod($logDirectory, 0755);
        }

        // Check if the log file exists and set permissions to 755
        $logFile = $logDirectory . '/laravel.log';
        if (file_exists($logFile)) {
            chmod($logFile, 0755);
        }
    }
}
