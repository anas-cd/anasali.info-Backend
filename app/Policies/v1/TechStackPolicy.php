<?php

namespace App\Policies\v1;

use App\Models\TechStack;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class TechStackPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user): Response|null
    {
        /**
         * NOTE: v1 is for my personal use only, thus a user role model (roles table) would be an overkill, hence the use of token abilities
         */

        if ($user->tokenCan("*")) {
            return Response::allow();
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TechStack $techStack): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        /**
         * NOTE: this authentication scheme is only for v1, since there well be only my account
         * TODO: handle http exception to be returned from API Response trait.
         */
        if ($user->tokenCan("tech:create")) {
            return Response::allow();
        } else {
            // - logging -
            Log::stack(['single', 'devLog', 'authLog'])
                ->debug(
                    'permission denied',
                    [
                        'user-id' => $user->id,
                        'permission' => 'tech:create'
                    ]
                );
            return Response::denyWithStatus(403);
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TechStack $techStack): Response
    {
        /**
         * NOTE: this authentication scheme is only for v1, since there well be only my account
         * TODO: handle http exception to be returned from API Response trait.
         */
        if ($user->tokenCan("tech:update")) {
            return Response::allow();
        } else {
            // - logging -
            Log::stack(['single', 'devLog', 'authLog'])
                ->debug(
                    'permission denied',
                    [
                        'user-id' => $user->id,
                        'permission' => 'tech:update'
                    ]
                );
            return Response::denyWithStatus(403);
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TechStack $techStack): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TechStack $techStack): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TechStack $techStack): bool
    {
        //
    }
}
