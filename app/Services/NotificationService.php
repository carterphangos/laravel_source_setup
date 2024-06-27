<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService extends BaseService
{
    protected $userService;

    public function __construct(Notification $notification, CacheService $cacheService, UserService $userService)
    {
        parent::__construct($notification, $cacheService);
        $this->userService = $userService;
    }

    public function createNotifications(string $message, $announcementId)
    {
        $notificationArray = [];
        $users = $this->userService->getAllUsersExceptCurrent();
        foreach ($users as $user) {
            $notificationData = [
                'user_id' => $user->id,
                'announcement_id' => $announcementId,
                'message' => $message,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $notificationArray[] = $notificationData;
        }

        return $this->createMany($notificationArray);
    }

    public function getNotifications($userId)
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('is_read', 'asc')
            ->orderByDesc('created_at')->paginate(10);
    }

    public function markNotificationAsRead($id)
    {
        $notification = $this->model->findOrFail($id);

        return $notification->update(['is_read' => true]);
    }

    public function markAllNotificationAsRead($userId)
    {
        return $this->model->where('user_id', $userId)
            ->where('is_read', 0)->update(['is_read' => 1]);
    }
}
