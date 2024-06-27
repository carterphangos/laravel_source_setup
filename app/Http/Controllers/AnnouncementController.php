<?php

namespace App\Http\Controllers;

use App\Enums\BaseLimit;
use App\Http\Requests\CreateAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Services\AnnouncementService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnnouncementController extends Controller
{
    private $announcementService;

    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    public function index(Request $request)
    {
        $announcements = $this->announcementService->getAnnouncements(
            $request->input('perPage', BaseLimit::LIMIT_10),
            $request->except('perPage')
        );

        return response()->json([
            'status' => true,
            'message' => 'All Announcements Get Successfully',
            'data' => $announcements,
        ], Response::HTTP_OK);
    }

    public function store(CreateAnnouncementRequest $request)
    {
        $announcement = $this->announcementService->createAnnouncement($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Announcement Create Successfully',
            'data' => $announcement,
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $announcement = $this->announcementService->getAnnouncement($id);

        return response()->json([
            'status' => true,
            'message' => 'Announcement Get Successfully',
            'data' => $announcement,
        ], Response::HTTP_OK);
    }

    public function update(UpdateAnnouncementRequest $request, $id)
    {
        $announcement = $this->announcementService->updateAnnouncement($id, $request->all());

        return response()->json([
            'status' => true,
            'message' => 'Announcement Updated Successfully',
            'data' => $announcement,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->announcementService->deleteAnnouncement($id);

        return response()->json([
            'status' => true,
            'message' => 'Announcement Deleted Successfully',
        ], Response::HTTP_OK);
    }
}
