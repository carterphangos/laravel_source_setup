<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Contracts\Pagination\Paginator;

class CommentService extends BaseService
{
    private $commentModel;

    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }

    public function getAllComments($perPage = 10, $filters = []): Paginator
    {
        $query = $this->model
            ->PostIdGreaterThan($filters['postId'] ?? false)
            ->AuthorIdGreaterThan($filters['authorId'] ?? false);

        $sortColumn = $filters['sortColumn'] ?? 'created_at';
        $sortOrder = $filters['sortOrder'] ?? 'desc';

        $columnSearch = ['content'];

        return $this->getAll($perPage, $query, $sortColumn, $sortOrder, $columnSearch, $filters['termSearch']);
    }
}
