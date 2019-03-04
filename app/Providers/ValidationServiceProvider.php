<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Custom Validation Provider
        Validator::extend('timestamp', function($attribute, $value, $parameters, $validator) {
            return abs(time() - $value[0]) < (60*$parameters[0]);
        });
        Validator::extend('timestamp', function($attribute, $value, $parameters, $validator) {
            return abs(time() - $value[0]) < (60*$parameters[0]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
