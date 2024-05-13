<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Contracts\Pagination\Paginator;

class CommentService extends BaseService
{
    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }

    public function getAllComments($perPage = 10, $filters = []): Paginator
    {
        $query = $this->model
            ->PostIdGreaterThan($filters['postId'] ?? false)
            ->AuthorIdGreaterThan($filters['authorId'] ?? false);

        $columnSearch = config('constants.COMMENTS_COLUMNS');

        return $this->getAll($perPage, $query, $filters, $columnSearch, $filters['termSearch']);
    }
}
