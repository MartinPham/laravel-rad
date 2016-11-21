<?php

namespace App\RAD\Policies;

use App\RAD\Models\Area;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AreaPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     */
    public function __construct()
    {
        //
    }

    public function access(User $user, Area $area)
    {
        return in_array($user->role, $area->roles, true);
    }
}
