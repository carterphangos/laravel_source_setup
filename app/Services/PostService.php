<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;

class PostService extends BaseService
{
    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function getAllPosts($perPage = 10, $filters = []): Paginator
    {
        $query = $this->model
            ->hasManyComments($filters['commentCount'] ?? false)
            ->AuthorIdGreaterThan($filters['authorId'] ?? false);

        $columnSearch = config('constants.POSTS_COLUMNS');

        return $this->getAll($perPage, $query, $filters, $columnSearch, $filters['termSearch']);
    }
}
