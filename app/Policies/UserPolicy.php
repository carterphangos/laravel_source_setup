<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BasePolicy
{
    public function update(User $user, User $model)
    {
        return $this->isAdmin($user) || $this->owns($user, $model);
    }

    public function delete(User $user, User $model)
    {
        return $this->isAdmin($user) || $this->owns($user, $model);
    }
}
