<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $filters = $request->except('page', 'perPage');
        $filters = [
            'postId' => $request->input('postId'),
            'authorId' => $request->input('authorId'),
            'sortColumn' => $request->input('sortColumn'),
            'sortOrder' => $request->input('sortOrder'),
            'termSearch' => $request->input('termSearch'),
        ];

        $comments = $this->commentService->getAllComments($perPage, $filters);

        return view('comments.index', compact('comments'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required',
            'post_id' => 'required',
        ]);

        $validatedData['user_id'] = 1;

        $this->commentService->create($validatedData);

        return redirect()->route('posts.show', $validatedData['post_id']);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'content' => 'required',
        ]);

        $comment = $this->commentService->update($id, $validatedData);

        return redirect()->route('posts.show', $comment->post_id);
    }

    public function destroy($id)
    {
        $this->commentService->delete($id);

        return redirect()->route('posts.index');
    }
}
