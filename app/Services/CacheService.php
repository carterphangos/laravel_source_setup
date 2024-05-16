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

    public function generateCacheKey($key, $filters)
    {
        if (! isset($filters['page'])) {
            $filters['page'] = 1;
        }

        ksort($filters);

        foreach ($filters as $filterKey => $filterValue) {
            $key .= $filterKey .  $filterValue;
        }

        return $key;
    }

    public function syncCache($model)
    {
        $name = class_basename($model);

        $this->forget($name);

        $totalPages = $model::paginate(BaseLimit::LIMIT_10)->lastPage();

        for ($page = 1; $page <= $totalPages; $page++) {
            $pageCacheKey = $name . 'page' . $page;

            $pageData = $model::paginate(BaseLimit::LIMIT_10, ['*'], 'page', $page);

            $this->put($pageCacheKey, $pageData, now()->addMinutes(5));
        }
    }
}
