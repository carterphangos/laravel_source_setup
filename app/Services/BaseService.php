<?php

namespace App\Services;

use App\Enums\BaseColumn;
use App\Enums\BaseLimit;
use App\Enums\BaseSort;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    protected $model;

    protected $cacheService;

    public function __construct(Model $model, CacheService $cacheService)
    {
        $this->model = $model;
        $this->cacheService = $cacheService;
    }

    public function getAll($perPage = null, $filters = [], $query = null, $columnSearch = null, $termSearch = null)
    {
        $query = $query ?: $this->model->newQuery();

        if ($termSearch && is_array($columnSearch)) {
            $query->where(function ($subQuery) use ($columnSearch, $termSearch) {
                foreach ($columnSearch as $column) {
                    $subQuery->orWhere($column, 'like', "%$termSearch%");
                }
            });
        }

        $sortColumn = $filters['sortColumn'] ?? BaseColumn::COLUMN_CREATED;
        $sortOrder = $filters['sortOrder'] ?? BaseSort::ORDER_DESC;

        $query->orderBy($sortColumn, $sortOrder);

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function getCachedData($perPage, $filters = [], $relations = [], $columnSearch = [], $termSearch = null, $query = null)
    {
        if (is_null($perPage)) {
            return $this->getAll($perPage, $filters, $query, $columnSearch, $termSearch);
        }

        if ($this->hasFilters($filters)) {
            return $this->fetchAndCacheData($perPage, $filters, $relations, $columnSearch, $termSearch, $cacheKey = null, $query);
        }

        $cacheKey = $this->cacheService->generateCacheKey(class_basename($this->model), $filters);
        $data = $this->cacheService->get($cacheKey);

        if (!$data) {
            $data = $this->fetchAndCacheData($perPage, $filters, $relations, $columnSearch, $termSearch, $cacheKey);
        }

        return $data;
    }

    private function fetchAndCacheData($perPage, $filters, $relations, $columnSearch, $termSearch, $cacheKey = null, $query = null)
    {
        $query = $query ?: $this->model->newQuery();

        $query->with($relations);

        $data = $this->getAll($perPage, $filters, $query, $columnSearch, $termSearch);

        if ($cacheKey) {
            $this->cacheService->put($cacheKey, $data, now()->addMinutes(5));
        }

        return $data;
    }

    private function hasFilters($filters): bool
    {
        $filterKeys = array_keys($filters);

        return count($filterKeys) > 1 || (count($filterKeys) === 1 && $filterKeys[0] !== 'page');
    }

    public function getById($id, array $with = [])
    {
        $finded = $this->model->with($with)->findOrFail($id);

        return $finded;
    }

    public function create(array $data, array $with = [])
    {
        $created = $this->model->create($data);

        $this->syncCache($with);

        return $created;
    }

    public function createMany(array $data, array $with = [])
    {
        $this->model->insert($data);

        $createds = $this->model->latest()->limit(count($data))->get();

        return $createds;
    }

    public function update($id, array $data, array $with = [])
    {
        $finded = $this->model->findOrFail($id);
        $updated = $finded->update($data);

        $this->syncCache($with);

        return $updated;
    }

    public function delete($id, array $with = [])
    {
        $finded = $this->model->findOrFail($id);
        $deleted = $finded->delete();

        $this->syncCache($with);

        return $deleted;
    }

    public function getDataToSync(array $with = [], $page = 1)
    {
        $query = $this->model->newQuery();

        if (!empty($with)) {
            $query->with($with);
        }

        $query->orderBy(BaseColumn::COLUMN_CREATED, BaseSort::ORDER_DESC);

        return $query->paginate(BaseLimit::LIMIT_10, ['*'], 'page', $page);
    }

    protected function syncCache(array $with = [])
    {
        $modelName = class_basename($this->model);
        $page = 1;
        while (true) {
            $data = $this->getDataToSync($with, $page);
            $key = $this->cacheService->generateCacheKey($modelName, ['page' => $page]);
            $this->cacheService->syncCache($key, $data);

            if (!$data->hasMorePages()) {
                break;
            }
            $page++;
        }
    }
}
