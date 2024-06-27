<?php

namespace App\Services;

use App\Enums\BaseColumn;
use App\Enums\BaseLimit;
use App\Enums\BaseSort;
use Illuminate\Support\Facades\Cache;

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

    public function flushAll(string $key)
    {
        Cache::tags($key)->flush();
    }

    public function formatCacheKey($filters)
    {
        if (! isset($filters['page'])) {
            $filters['page'] = 1;
        }

        ksort($filters);

        $parts = [];
        foreach ($filters as $filterKey => $filterValue) {
            $parts[] = ucfirst($filterKey).':'.$filterValue;
        }

        return implode('-', $parts);
    }

    public function generateCacheKey($key, $filters)
    {
        return $key.'-'.$this->formatCacheKey($filters);
    }

    public function syncCache($key, $data)
    {
        $this->forget($key);

        $this->put($key, $data, now()->addMinutes(5));
    }

    public function syncModelData($modelClass, $with = [])
    {
        $model = new $modelClass;
        $modelName = class_basename($model);
        $page = 1;

        while (true) {
            $query = $model->newQuery()->with($with);
            $data = $query->orderBy(BaseColumn::COLUMN_CREATED, BaseSort::ORDER_DESC)->paginate(BaseLimit::LIMIT_10, ['*'], 'page', $page);

            $filters = ['page' => $page];
            $key = $this->generateCacheKey($modelName, $filters);

            $this->put($key, $data, now()->addMinutes(5));

            if (! $data->hasMorePages()) {
                break;
            }
            $page++;
        }
    }
}
