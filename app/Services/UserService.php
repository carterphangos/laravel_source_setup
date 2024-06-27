<?php

namespace App\Services;

use App\Enums\UserColumns;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    protected $uploadService;

    public function __construct(User $user, CacheService $cacheService, UploadService $uploadService)
    {
        parent::__construct($user, $cacheService);
        $this->uploadService = $uploadService;
    }

    public function getUsers($perPage, $filters = [])
    {
        $relations = [];
        $query = $this->model->newQuery();
        
        if (isset($filters['isAuthor'])) {
            return $query->isAuthor()->get();
        }

        $columnSearch = [
            UserColumns::UserName,
        ];

        return $this->getCachedData($perPage, $filters, $relations, $columnSearch, $filters['termSearch'] ?? null, $query);
    }

    public function createUser(array $data)
    {
        Gate::authorize('create', User::class);

        if (isset($data['avatar'])) {
            $result = $this->uploadService->uploadFile($data['avatar']);
            $data['avatar'] = $result->getSecurePath();
        }

        return $this->create($data);
    }

    public function updateUser($id, array $data)
    {
        Gate::authorize('update', User::class);

        if (isset($data['avatar'])) {
            $result = $this->uploadService->uploadFile($data['avatar']);
            $data['avatar'] = $result->getSecurePath();
        }

        return $this->update($id, $data);
    }

    public function updateUserPassword($request)
    {
        Gate::authorize('update', User::class);

        $user = User::findOrFail($request->user_id);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        $user->tokens()->delete();
    }

    public function deleteUser($id)
    {
        Gate::authorize('delete', User::class);

        return $this->delete($id);
    }

    public function getAllUsersExceptCurrent()
    {
        return User::all()->except(Auth::id());
    }
}
