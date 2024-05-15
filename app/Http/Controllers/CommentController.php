<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;
use App\Enums\BaseLimit;
use App\Events\NewCommentEvent;

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

        return view('comments.index', compact('comments'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required',
            'post_id' => 'required',
        ]);

        $validatedData['user_id'] = 1;

        $comment = $this->commentService->create($validatedData);

        event(new NewCommentEvent($comment));

        return redirect()->route('posts.show', $validatedData['post_id']);
    }

    public function update(UpdateCommentRequest $request, $id)
    {
        $comment = $this->commentService->update($id, $request->all());

        return redirect()->route('posts.show', $comment->post_id);
    }

    public function destroy($id)
    {
        $this->commentService->delete($id);

        return redirect()->route('posts.index');
    }
}
