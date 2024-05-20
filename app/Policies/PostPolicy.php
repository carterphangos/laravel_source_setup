<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy extends BasePolicy
{
    public function update(User $user, Post $post)
    {
        return $this->isAdmin($user) || $this->owns($user, $post);
    }

    public function delete(User $user, Post $post)
    {
        return $this->isAdmin($user) || $this->owns($user, $post);
    }
}
