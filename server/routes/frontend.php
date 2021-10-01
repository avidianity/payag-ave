<?php

use App\Http\Requests\EmailVerificationRequest;

Route::get('/login', function () {
    return redirect(frontend('/login'));
})->name('login');

Route::get('/forgot-password', function () {
    return redirect(frontend('/forgot-password'));
})->name('password.request');

Route::get('/reset-password/{token}', function ($token) {
    return redirect(frontend(sprintf('/reset-password/%s', $token)));
})->name('password.reset');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    /**
     * @var \App\Models\User
     */
    $user = $request->user();

    if ($request->has('request_id')) {
        $changeEmailRequest = $user->changeEmailRequests()->findOrFail($request->input('request_id'));
        $changeEmailRequest->approved = true;
        $user->update(['email' => $changeEmailRequest->email, 'email_verified_at' => $user->freshTimestamp()]);
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
