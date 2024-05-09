<?php

namespace App\Services;

use App\Models\Comment;

class CommentService extends BaseService
{
    private $commentModel;

    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }
}
