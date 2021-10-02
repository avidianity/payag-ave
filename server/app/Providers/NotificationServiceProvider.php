<?php

namespace App\Providers;

use App\Channels\SemaphoreChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        'semaphore' => SemaphoreChannel::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('semaphore', function ($app) {
                return $app->make('semaphore');
            });
        });
    }
}
