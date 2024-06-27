<?php

namespace App\Http\Controllers;

use App\Enums\BaseLimit;
use App\Http\Requests\CreateTrackingRequest;
use App\Services\TrackingService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrackingController extends Controller
{
    private $trackingService;

    public function __construct(TrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    public function announStats()
    {
        $stats = $this->trackingService->getViewStats();

        return response()->json([
            'status' => true,
            'message' => 'View stats retrieved successfully',
            'data' => $stats,
        ], Response::HTTP_OK);
    }

    public function dailyStats()
    {
        $stats = $this->trackingService->getDailyViewStats();

        return response()->json([
            'status' => true,
            'message' => 'View stats retrieved successfully',
            'data' => $stats,
        ], Response::HTTP_OK);
    }

    public function check($announcementId)
    {
        $check = $this->trackingService->getCheck($announcementId);

        return response()->json([
            'status' => true,
            'message' => 'Tracking Check Successfully',
            'data' => $check,
        ], Response::HTTP_OK);
    }

    public function store(CreateTrackingRequest $request)
    {
        $tracking = $this->trackingService->create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Tracking Created Successfully',
            'data' => $tracking,
        ], Response::HTTP_OK);
    }
}
