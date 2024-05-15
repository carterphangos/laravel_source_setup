<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRoles;

class BasePolicy
{
    public function isAdmin(User $user)
    {
        return $user->role === UserRoles::ADMIN_ROLE;
    }

    public function owns(User $user, $subject)
    {
        return $user->id === $subject->user_id;
    }
}
