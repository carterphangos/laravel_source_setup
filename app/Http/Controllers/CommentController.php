<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all();
        return view('comments.index', compact('comments'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required',
            'post_id' => 'required',
        ]);
        
        $validatedData['user_id'] = 1;

        Comment::create($validatedData);
        return redirect()->route('posts.show',  $validatedData['post_id']);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'content' => 'required',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($validatedData);
        return redirect()->route('posts.show', $comment->post_id);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $postId = $comment->post_id;
        $comment->delete();
        return redirect()->route('posts.show', $postId);
    }
}
