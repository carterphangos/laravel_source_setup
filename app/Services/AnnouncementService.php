<?php

namespace App\Services;

use App\Enums\AnnouncementColumns;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AnnouncementService extends BaseService
{
    protected $categoryService;

    protected $notificationService;

    public function __construct(Announcement $announcement, CacheService $cacheService, CategoryService $categoryService, NotificationService $notificationService)
    {
        parent::__construct($announcement, $cacheService);
        $this->categoryService = $categoryService;
        $this->notificationService = $notificationService;
    }

    public function getAnnouncements($perPage, $filters = [])
    {
        $relations = ['author', 'categories'];
        $query = $this->model->newQuery();

        if (isset($filters['category'])) {
            $query->hasCategory($filters['category']);
        }

        if (isset($filters['author'])) {
            $query->belongToAuthor($filters['author']);
        }

        $columnSearch = [
            AnnouncementColumns::Title,
            AnnouncementColumns::Content,
        ];

        return $this->getCachedData($perPage, $filters, $relations, $columnSearch, $filters['termSearch'] ?? null, $query);
    }

    public function getAnnouncement($id)
    {
        return $this->getById($id, ['author', 'categories']);
    }

    public function createAnnouncement(array $data)
    {
        $announcement = $this->create($data, ['author', 'categories']);

        if (isset($data['categories'])) {
            $announcement->categories()->sync($data['categories']);
        }

        $importantCategory = $this->categoryService->findByName('Important');
        if ($importantCategory && in_array($importantCategory->id, $data['categories'])) {
            $this->notificationService->createNotifications('🚨 Important announcement: '.$announcement->title.'', $announcement->id);
        }

        return $this->model->with(['author', 'categories'])->find($announcement->id);
    }

    public function createAnnouncements(array $data, $categoryId)
    {
        $announcements = $this->createMany($data);

        foreach ($announcements as $announcement) {
            $announcement->categories()->attach($categoryId);
        }

        return $this->model->with(['author', 'categories'])->findMany($announcements->pluck('id'));
    }

    public function updateAnnouncement($id, array $data)
    {
        Gate::authorize('update', User::class);

        $announcement = $this->update($id, $data, ['author', 'categories']);

        return $announcement;
    }

    public function deleteAnnouncement($id)
    {
        Gate::authorize('delete', User::class);

        return $this->delete($id, ['author', 'categories']);
    }
}
