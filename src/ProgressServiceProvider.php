<?php

namespace Noxxie\Database\Progress;

use Illuminate\Support\ServiceProvider;

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

        // Register the progress driver type.
        foreach (config('database.connections') as $conn => $config) {
            if (!isset($config['driver']) || $config['driver'] != 'progress') {
                continue;
            }

            $this->app['db']->extend($conn, function ($config, $name) {
                $connector = new ProgressConnector();
                
                return new ProgressConnection(
                    $connector->connect($config), 
                    $config['database'] ?? null, 
                    $config['prefix'] ?? null, 
                    $config,
                );
            });
        }
    }
}
