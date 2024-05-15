<?php

namespace App\Services;

use App\Enums\PostColumns;
use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;

class PostService extends BaseService
{
    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function getAllPosts($perPage, $filters = []): Paginator
    {
        $query = $this->model
            ->hasManyComments($filters['commentCount'] ?? false)
            ->AuthorIdGreaterThan($filters['authorId'] ?? false);

        $columnSearch = [
            PostColumns::Title,
            PostColumns::Content,
        ];

        return $this->getAll($perPage, $query, $filters, $columnSearch, $filters['termSearch'] ?? null);
    }
}
