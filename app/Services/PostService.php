<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;

class PostService extends BaseService
{
    protected $post;

    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function getAllPosts($perPage = 10, $filters = []): Paginator
    {
        $query = $this->model
            ->hasManyComments($filters['commentCount'] ?? false)
            ->AuthorIdGreaterThan($filters['authorId'] ?? false);

        $sortColumn = $filters['sortColumn'] ?? 'created_at';
        $sortOrder = $filters['sortOrder'] ?? 'desc';

        $columnSearch = ['title'];

        return $this->getAll($perPage, $query, $sortColumn, $sortOrder, $columnSearch, $filters['termSearch']);
    }
}
