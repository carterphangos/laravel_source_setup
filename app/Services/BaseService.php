<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll($perPage, $query, $filters = [], $columnSearch = null, $termSearch = null): Paginator
    {
        $sortColumn = $filters['sortColumn'] ?? 'created_at';
        $sortOrder = $filters['sortOrder'] ?? 'desc';
      
        $query->orderBy($sortColumn, $sortOrder);

        if ($termSearch && is_array($columnSearch)) {
            $query->where(function ($subQuery) use ($columnSearch, $termSearch) {
                foreach ($columnSearch as $column) {
                    $subQuery->orWhere($column, 'like', "%$termSearch%");
                }
            });
        }

        return $query->paginate($perPage);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        $model->delete();

        return $model;
    }
}
