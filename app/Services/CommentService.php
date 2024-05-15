<?php

namespace App\Services;

use App\Enums\CommentColumns;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\Paginator;

class CommentService extends BaseService
{
    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }

    public function getAllComments($perPage, $filters = []): Paginator
    {
        $query = $this->model
            ->PostIdGreaterThan($filters['postId'] ?? false)
            ->AuthorIdGreaterThan($filters['authorId'] ?? false);

        $columnSearch = [CommentColumns::Title];

        return $this->getAll($perPage, $query, $filters, $columnSearch, $filters['termSearch']);
    }
}
