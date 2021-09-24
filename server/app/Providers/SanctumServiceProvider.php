<?php

namespace App\Providers;

use App\Guards\Sanctum as Guard;
use Illuminate\Auth\RequestGuard;
use Laravel\Sanctum\SanctumServiceProvider as ServiceProvider;

class SanctumServiceProvider extends ServiceProvider
{
    /**
     * Register the guard.
     *
     * @param \Illuminate\Contracts\Auth\Factory  $auth
     * @param array $config
     * @return RequestGuard
     */
    protected function createGuard($auth, $config)
    {
        return new RequestGuard(
            new Guard($auth, config('sanctum.expiration'), $config['provider']),
            $this->app['request'],
            $auth->createUserProvider($config['provider'] ?? null)
        );
    }
}
