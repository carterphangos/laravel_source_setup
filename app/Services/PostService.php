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

}
