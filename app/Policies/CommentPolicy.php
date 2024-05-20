<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy extends BasePolicy
{
    public function update(User $user, Comment $comment)
    {
        return $this->isAdmin($user) || $this->owns($user, $comment);
    }

    public function delete(User $user, Comment $comment)
    {
        return $this->isAdmin($user) || $this->owns($user, $comment);
    }
}
