<?php

namespace App\Policies\v1;

use App\Models\Hobby;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HobbyPolicy
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
    public function view(User $user, Hobby $hobby): bool
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
        return $user->tokenCan("hobby:create")
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Hobby $hobby): Response
    {
        /**
         * NOTE: this authentication scheme is only for v1, since there well be only my account
         * TODO: handle http exception to be returned from API Response trait.
         */
        return $user->tokenCan("hobby:update")
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Hobby $hobby): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Hobby $hobby): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Hobby $hobby): bool
    {
        //
    }
}
