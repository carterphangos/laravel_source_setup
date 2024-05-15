<?php

namespace App\Policies;

use App\Models\User;

class BasePolicy
{
    public function isAdmin(User $user)
    {
        return $user->role === 'admin';
    }

    public function owns(User $user, $subject)
    {
        return $user->id === $subject->user_id;
    }
}
