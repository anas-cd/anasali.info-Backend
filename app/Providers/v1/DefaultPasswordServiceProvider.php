<?php

namespace App\Providers\v1;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class DefaultPasswordServiceProvider extends ServiceProvider
{

    /**
     * NOTE: This service provider is an overkill but just to showcase the usage in projects!. :)
     *
     * NOTE: This service provider is versioned since future changes in password rules can be a breacking change of current API users.
     */

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });
    }
}
