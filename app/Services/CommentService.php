<?php

namespace App\Services;

use App\Enums\CommentColumns;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\Paginator;

class CommentService extends BaseService
{
    protected $cacheService;

    public function __construct(Comment $comment, CacheService $cacheService)
    {
        parent::__construct($comment);
        $this->cacheService = $cacheService;
    }

    public function getAllComments($perPage, $filters = []): Paginator
    {
        $cacheKey = $this->cacheService->generateCacheKey('Comment', $filters);
        $comments = $this->cacheService->get($cacheKey);

        if (! $comments) {

            $query = $this->model
                ->PostIdGreaterThan($filters['postId'] ?? false)
                ->AuthorIdGreaterThan($filters['authorId'] ?? false);

            $columnSearch = [CommentColumns::Title];

            $comments = $this->getAll($perPage, $query, $filters, $columnSearch, $filters['termSearch'] ?? null);

            $this->cacheService->put($cacheKey, $comments, now()->addMinutes(5));
        }

        return $comments;
    }

    public function createComment(array $data)
    {
        $comment = $this->create($data);

        $this->cacheService->syncCache($comment);

        return $comment;
    }

    public function updateComment($id, array $data)
    {
        $comment = $this->update($id, $data);

        $this->cacheService->syncCache($comment);

        return $comment;
    }

    public function deleteComment($id)
    {
        $comment = $this->delete($id);

        $this->cacheService->syncCache($comment);
    }
}
