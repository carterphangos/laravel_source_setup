<?php

namespace App\Services;

use App\Models\Tracking;
use Illuminate\Support\Facades\DB;

class TrackingService extends BaseService
{
    public function __construct(Tracking $tracking, CacheService $cacheService)
    {
        parent::__construct($tracking, $cacheService);
    }

    public function getViewStats()
    {
        return $this->model
            ->select('announcement_id', DB::raw('COUNT(*) as views'))
            ->groupBy('announcement_id')
            ->orderByDesc('views')
            ->with('announcement')
            ->take(10)
            ->get();
    }

    public function getDailyViewStats()
    {
        return $this->model
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as views'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();
    }

    public function getCheck($announcementId)
    {
        $userId = auth()->user()->id;
        $today = now()->toDateString();

        return $this->model->where('announcement_id', $announcementId)
            ->where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->exists();
    }
}
