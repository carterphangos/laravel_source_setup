<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function show($userId)
    {
        $notifications = $this->notificationService->getNotifications($userId);

        return response()->json([
            'status' => true,
            'message' => 'All Notifications Get Successfully',
            'data' => $notifications,
        ], Response::HTTP_OK);
    }

    public function update($id)
    {
        $notification = $this->notificationService->markNotificationAsRead($id);

        return response()->json([
            'status' => true,
            'message' => 'Notification Mark As Read Successfully',
            'data' => $notification,
        ], Response::HTTP_OK);
    }

    public function updateAll($userId)
    {
        $notifications = $this->notificationService->markAllNotificationAsRead($userId);

        return response()->json([
            'status' => true,
            'message' => 'All Notifications Mark As Read  Get Successfully',
            'data' => $notifications,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->notificationService->delete($id);

        return response()->json([
            'status' => true,
            'message' => 'Notification Deleted Successfully',
        ], Response::HTTP_OK);
    }
}
