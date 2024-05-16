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
        $this->logService->info('This is an info message.');
        $this->logService->error('This is an error message.', ['error' => 'Something went wrong']);
        $this->logService->warning('This is an warning message.', ['warning' => 'Something crashed']);

        $cacheKey = $this->cacheService->generateCacheKey('Post', $filters);
        $posts = $this->cacheService->get($cacheKey);

        if (!$posts) {
            $query = $this->model
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
