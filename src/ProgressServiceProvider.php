<?php

namespace Noxxie\Database\Progress;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;

use Noxxie\Database\ProgressConnector;
use Noxxie\Database\ProgressConnection;

class ProgressServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        // Register the progress driver type
        foreach (config('database.connections') as $conn => $config) {
            if (!isset($config['driver']) || $config['driver'] != 'progress') {
                continue;
            }

            $this->app['db']->extend($conn, function($config, $name) {
                $connector = new ProgressConnector();
                return new ProgressConnection($connector->connect($config), null, null, $config);
            });
        }
    }
}
