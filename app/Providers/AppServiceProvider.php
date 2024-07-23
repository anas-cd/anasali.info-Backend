<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Hobby;
use App\Models\Profile;
use App\Policies\v1\CoursePolicy;
use App\Policies\v1\EducationPolicy;
use App\Policies\v1\ExperiencePolicy;
use App\Policies\v1\HobbyPolicy;
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
        Gate::policy(Experience::class, ExperiencePolicy::class);
        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Hobby::class, HobbyPolicy::class);
    }
}
