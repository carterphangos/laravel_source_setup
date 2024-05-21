<?php

namespace App\Http\Controllers;

use App\Enums\BaseLimit;
use App\Events\NewCommentEvent;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $comments = $this->commentService->getAllComments(
            $request->input('perPage', BaseLimit::LIMIT_10),
            $request->except('perPage')
        );

        return response()->json([
            'status' => true,
            'message' => 'All Comments Get Successfully',
            'data' => $comments,
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required',
            'post_id' => 'required',
            'user_id' => 'required',
        ]);

        $comment = $this->commentService->createComment($validatedData);

        event(new NewCommentEvent($comment));

        return redirect()->route('posts.show', $validatedData['post_id']);
    }

    public function update(UpdateCommentRequest $request, $id)
    {
        $comment = $this->commentService->updateComment($id, $request->all());

        return response()->json([
            'status' => true,
            'message' => 'Comment Edited Successfully',
            'data' => $comment,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->commentService->deleteComment($id);

        return response()->json([
            'status' => true,
            'message' => 'Comment Deleted Successfully',
        ], Response::HTTP_OK);
    }
}
