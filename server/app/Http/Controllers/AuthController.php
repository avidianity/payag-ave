<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendVerificationMailRequest;
use App\Http\Requests\SendForgotPasswordMailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::whereEmail($data['email'])->firstOrFail();

        if ($user->blocked_until !== null) {
            $date = Carbon::parse($user->blocked_until);
            if ($date->isFuture()) {
                return response(['message' => __('auth.locked')], 403);
            }
        }

        if (Cache::get($user->getLockingKey(), 0) >= config('auth.blocking.retries')) {
            $minutes = config('auth.blocking.minutes');
            $seconds = config('auth.blocking.seconds');

            $user->blocked_until = now()
                ->addSeconds($minutes)
                ->addMinutes($seconds);

            $user->resetLock();
            $user->save();

            return response(['message' => __('auth.locked_time', [
                'minutes' => $minutes,
                'seconds' => $seconds,
            ])], 429);
        }

        if (!$user->status) {
            return response(['message' => __('auth.inactive')], 401);
        }

        if (!Hash::check($data['password'], $user->password)) {
            $user->incrementLock();
            return response(['message' => __('auth.password')], 401);
        }

        if (!$user->email_verified_at) {
            $user->incrementLock();
            return response(['message' => __('auth.unverified')], 401);
        }

        $token = $user->createToken(Str::random());

        $user->blocked_until = null;
        $user->resetLock();

        $user->save();

        return [
            'token' => $token->plainTextToken,
            'user' => new UserResource($user),
        ];
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['status'] = false;
        $data['role'] = User::CUSTOMER;

        $user = User::create($data);

        event(new Registered($user));

        return response('', 204);
    }

    public function resendVerificationEmail(ResendVerificationMailRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->firstOrFail();

        if (!Hash::check($data['password'], $user->password)) {
            return response(['message' => __('auth.password')], 403);
        }

        $user->sendEmailVerificationNotification();

        return response('', 204);
    }

    public function check(Request $request)
    {
        return $request->user('sanctum');
    }

    public function sendForgotPasswordEmail(SendForgotPasswordMailRequest $request)
    {
        $data = $request->validated();

        $status = Password::sendResetLink($data);

        if ($status !== Password::RESET_LINK_SENT) {
            return response(['errors' => [
                'email' => [__($status)]
            ]], 422);
        }

        return response(['status' => __($status)]);
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response(['errors' => [
                'email' => [__($status)]
            ]], 422);
        }

        return response(['status' => __($status)]);
    }
}
