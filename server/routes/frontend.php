<?php

use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

Route::get('/login', function () {
    return redirect(frontend('/login'));
})->name('login');

Route::get('/forgot-password', function () {
    return redirect(frontend('/forgot-password'));
})->name('password.request');

Route::get('/reset-password/{token}', function ($token) {
    return redirect(frontend("/reset-password/$token"));
})->name('password.reset');

Route::get('/service-unavailable', function (Request $request) {
    if ($request->expectsJson()) {
        return response('', 503);
    }

    return redirect(frontend('/service-unavailable'));
})->name('maintenance-mode');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    /**
     * @var \App\Models\User
     */
    $user = $request->user();

    if ($request->has('request_id')) {
        $changeEmailRequest = $user->changeEmailRequests()->findOrFail($request->input('request_id'));
        $changeEmailRequest->approved = true;
        $user->update(['email' => $changeEmailRequest->email, 'email_verified_at' => $user->freshTimestamp()]);
        event(new Verified($user));
    } else {
        $request->fulfill();
    }

    /**
     * @var \App\Models\Token
     */
    $token = $user->currentAccessToken();

    $token->delete();

    return redirect(frontend('/email-verified'));
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');
