<?php

namespace App\Listeners;

use App\Notifications\RegisteredSMS;
use Illuminate\Auth\Events\Registered;
use Illuminate\Notifications\Notifiable;

class SendRegisteredSMSNotification
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        echo 'ehe';
        optional($event->user)->notify(new RegisteredSMS(
            __('sms.registered', [
                'app' => config('app.name'),
                'name' => $event->user->name,
            ])
        ));
    }
}
