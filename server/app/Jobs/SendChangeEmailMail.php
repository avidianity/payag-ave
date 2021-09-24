<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\ReVerifyEmail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SendChangeEmailMail implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $email;

    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var \App\Models\ChangeEmailRequest
     */
    public $request;

    /**
     * Create a new job instance.
     *
     * @param string $email
     * @param \App\Models\User $user
     * @param \App\Models\ChangeEmailRequest $request
     * @return void
     */
    public function __construct($email, $user, $request)
    {
        $this->email = $email;
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mail = $this->buildMailMessage($this->makeSignedUrl());


        Notification::send(new User(['email' => $this->email]), new ReVerifyEmail($mail));
    }

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('Re-Verify Email Address'))
            ->line(Lang::get('You have changed your email address.'))
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $url)
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }

    protected function makeSignedUrl()
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->user->getKey(),
                'hash' => sha1($this->email),
                'request_id' => $this->request->getKey(),
                'token' => $this->user->createToken(Str::random(10))->plainTextToken,
            ]
        );
    }
}
