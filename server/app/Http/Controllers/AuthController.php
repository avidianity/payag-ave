<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendVerificationMailRequest;
use App\Http\Requests\SendForgotPasswordMailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\RegisteredNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::whereEmail($data['email'])->firstOrFail();

        if (!Hash::check($data['password'], $user->password)) {
            return response(['message' => 'Password is incorrect.'], 401);
        }

        if (!$user->email_verified_at) {
            return response(['message' => 'Email is not verified.'], 401);
        }

        $token = $user->createToken(Str::random());

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
            return response(['message' => 'Password is incorrect.'], 403);
        }

        $user->sendEmailVerificationNotification();

        return response('', 204);
    }

    public function check(Request $request)
    {
        return $request->user();
    }

    public function sendForgotPasswordEmail(SendForgotPasswordMailRequest $request)
    {
        $data = $request->validated();

        $status = Password::sendResetLink($data['email']);

        if ($status !== Password::RESET_LINK_SENT) {
            return response(['errors' => [
                'email' => [__($status)]
            ]], 422);
        }

        return response(['status' => __($status)]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
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
