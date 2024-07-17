<?php

namespace App\Providers;

use App\Models\Education;
use App\Models\Profile;
use App\Policies\v1\EducationPolicy;
use App\Policies\v1\ProfilePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /* --- policies ---*/
        Gate::policy(Profile::class, ProfilePolicy::class);
        Gate::policy(Education::class, EducationPolicy::class);
    }
}
