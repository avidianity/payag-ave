<?php

namespace App\Guards;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Guard;
use Laravel\Sanctum\TransientToken;
use Laravel\Sanctum\Sanctum as LaravelSanctum;

class Sanctum extends Guard
{
    /**
     * Retrieve the authenticated user for the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        foreach (Arr::wrap(config('sanctum.guard', 'web')) as $guard) {
            if ($user = $this->auth->guard($guard)->user()) {
                return $this->supportsTokens($user)
                    ? $user->withAccessToken(new TransientToken)
                    : $user;
            }
        }

        /**
         * Check not only bearer token but input as well.
         */
        if ($token = $this->getToken($request)) {
            $model = LaravelSanctum::$personalAccessTokenModel;

            $accessToken = $model::findToken($token);

            if (
                !$accessToken ||
                ($this->expiration &&
                    $accessToken->created_at->lte(now()->subMinutes($this->expiration))) ||
                !$this->hasValidProvider($accessToken->tokenable)
            ) {
                return;
            }

            return $this->supportsTokens($accessToken->tokenable) ? $accessToken->tokenable->withAccessToken(
                tap($accessToken->forceFill(['last_used_at' => now()]))->save()
            ) : null;
        }
    }

    protected function getToken(Request $request)
    {
        if ($token = $request->bearerToken()) {
            return $token;
        }

        return $request->input('token');
    }
}
