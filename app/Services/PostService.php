<?php

namespace App\Services;

use App\Enums\PostColumns;
use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;

class PostService extends BaseService
{
    protected $cacheService;

    protected $logService;

    public function __construct(Post $post, CacheService $cacheService, LogService $logService)
    {
        parent::__construct($post);
        $this->cacheService = $cacheService;
        $this->logService = $logService;
    }

    public function getAllPosts($perPage, $filters = []): Paginator
    {
        $this->logService->info('this is a info message', 'path/to/getAllPost.log');
        $this->logService->error('this is an error message', 'path/to/getAllPost.log');
        $this->logService->warn('this is a warn message', 'path/to/getAllPost.log');

        $cacheKey = $this->cacheService->generateCacheKey('Post', $filters);
        $posts = $this->cacheService->get($cacheKey);

        if (! $posts) {
            $query = $this->model
                ->with(['user', 'comments', 'comments.user'])
                ->hasManyComments($filters['commentCount'] ?? false)
                ->AuthorIdGreaterThan($filters['authorId'] ?? false);

            $columnSearch = [
                PostColumns::Title,
                PostColumns::Content,
            ];

            $posts = $this->getAll($perPage, $query, $filters, $columnSearch, $filters['termSearch'] ?? null);

            $this->cacheService->put($cacheKey, $posts, now()->addMinutes(5));
        }

        return $posts;
    }

    public function createPost(array $data)
    {
        $post = $this->create($data);

        $this->cacheService->syncCache($post);

        return $post;
    }

    public function updatePost($id, array $data)
    {
        $post = $this->update($id, $data);

        $this->cacheService->syncCache($post);

        return $post;
    }

    public function deletePost($id)
    {
        $post = $this->delete($id);

        $this->cacheService->syncCache($post);
    }
}
