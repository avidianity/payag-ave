
<?php

use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    /**
     * @var \App\Models\User
     */
    $user = $request->user();

    if ($request->has('request_id')) {
        $changeEmailRequest = $user->requests()->findOrFail($request->input('request_id'));
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

    return redirect(config('urls.frontend') . '/email-verified');
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::get('/login', function () {
    return redirect(config('urls.frontend') . '/login');
})->name('login');

Route::get('/forgot-password', function () {
    return redirect(config('urls.frontend') . '/forgot-password');
})->name('password.request');

Route::get('/reset-password/{token}', function ($token) {
    return redirect(config('urls.frontend') . sprintf('/reset-password/%s', $token));
})->name('password.reset');
