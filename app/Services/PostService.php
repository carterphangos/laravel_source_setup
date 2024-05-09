<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;

class PostService extends BaseService
{
    private $postModel;

    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function getAllPosts($perPage = 10, $filters = []): Paginator
    {
        return $this->getAll($perPage, $filters);
    }

    public function getFilteredPosts($perPage = 10, $filters = []): Paginator
    {
        $query = Post::query();

        $commentCount = $filters['commentCount'] ?? null;

        $query->has('comments', '>', $commentCount);

        return $query->paginate($perPage);
    }
}
