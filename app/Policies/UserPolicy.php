<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BasePolicy
{
    public function getAll(User $user)
    {
        return $this->isAdmin($user);
    }

    public function create(User $user)
    {
        return $this->isAdmin($user);
    }

    public function update(User $user)
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user)
    {
        return $this->isAdmin($user);
    }
}
