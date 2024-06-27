<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class CategoryService extends BaseService
{
    protected $cacheService;

    public function __construct(Category $category, CacheService $cacheService)
    {
        parent::__construct($category, $cacheService);
    }

    public function createCategory(array $data)
    {
        Gate::authorize('create', User::class);

        return $this->create($data);
    }

    public function findByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function updateCategory($id, array $data)
    {
        Gate::authorize('update', User::class);

        return $this->update($id, $data);
    }

    public function deleteCategory($id)
    {
        Gate::authorize('delete', User::class);

        return $this->delete($id);
    }
}
