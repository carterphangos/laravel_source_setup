<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Http\Resources\PostCollection;
use App\Models\User;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $posts = $user->posts;
        $comments = $user->comments;

        return response()->json([
            'posts' => new PostCollection($posts),
            'comments' => new CommentCollection($comments),
        ]);
    }
}
