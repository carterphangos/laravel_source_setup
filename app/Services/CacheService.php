<?php

namespace App\Services;

use App\Enums\BaseLimit;
use Illuminate\Support\Facades\Cache;
use App\Models\Post;
use App\Models\Comment;

class CacheService
{
    public function get($key)
    {
        return Cache::get($key);
    }

    public function put($key, $value, $ttl)
    {
        Cache::put($key, $value, $ttl);
    }

    public function forget($key)
    {
        Cache::forget($key);
    }

    public function generateCacheKey($model, $filters)
    {
        $key = $model;

        if (isset($filters['commentCount']) && $model === 'posts') {
            $key .= 'Has' . $filters['commentCount'] . 'Comment';
        }

        if (isset($filters['authorId']) && $model === 'posts') {
            $key .= 'HasId' . $filters['authorId'];
        }

        if (isset($filters['postId']) && $model === 'comments') {
            $key .= 'Has' . $filters['postId'] . 'Comment';
        }

        if (isset($filters['authorId']) && $model === 'comments') {
            $key .= 'HasId' . $filters['authorId'];
        }

        $key .= 'Page' . ($filters['page'] ?? 1);

        return $key;
    }

    public function syncCache($model)
    {
        $name = class_basename($model);

        $this->forget($name);

        $totalPages = $model::paginate(BaseLimit::LIMIT_10)->lastPage();

        for ($page = 1; $page <= $totalPages; $page++) {
            $pageCacheKey = $name . 'Page' . $page;

            $pageData = $model::paginate(BaseLimit::LIMIT_10, ['*'], 'page', $page);

            $this->put($pageCacheKey, $pageData, now()->addMinutes(5));
        }
    }
}
