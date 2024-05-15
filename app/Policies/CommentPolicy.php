<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

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
