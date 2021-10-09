<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Get a model binding from a route
         *
         * @param string $param
         * @param string $class
         * @param mixed $default
         * @return \Illuminate\Database\Eloquent\Model|mixed
         */
        Request::macro('routeModel', function ($param, $class, $default = null) {
            $route = $this->route($param, $default);

            if ($route instanceof Model) {
                return $route;
            }

            $model = $class::find($route);

            if (!$model) {
                return $default;
            }

            return $model;
        });
    }
}
