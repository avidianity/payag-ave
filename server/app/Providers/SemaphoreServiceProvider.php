<?php

namespace App\Providers;

use App\Channels\SemaphoreChannel;
use Avidian\Semaphore\Client;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
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

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('semaphore', function ($app) {
                return $app->make(SemaphoreChannel::class);
            });
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
