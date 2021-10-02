<?php

namespace App\Providers;

use Avidian\Semaphore\Client;
use Illuminate\Support\ServiceProvider;

class SemaphoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function () {
            $env = config('app.env');

            return new Client(config('semaphore.key'), [
                'apiBase' => config("semaphore.urls.$env"),
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
