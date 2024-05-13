<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Http\Resources\PostCollection;
use App\Models\Comment;
use App\Models\Post;

class UserController extends Controller
{
    public function show($id)
    {
        $posts = Post::with('user')->where('user_id', $id)->get();
        $comments = Comment::with('user')->where('user_id', $id)->get();

        return response()->json([
            'posts' => new PostCollection($posts),
            'comments' => new CommentCollection($comments),
        ]);
    }
}
