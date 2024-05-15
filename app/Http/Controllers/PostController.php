<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $filters = $request->except('page', 'perPage');

        $filters = [
            'commentCount' => $request->input('commentCount'),
            'authorId' => $request->input('authorId'),
            'sortColumn' => $request->input('sortColumn'),
            'sortOrder' => $request->input('sortOrder'),
            'termSearch' => $request->input('termSearch'),
        ];

        $posts = $this->postService->getAllPosts($perPage, $filters);

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $validatedData['user_id'] = 1;

        $this->postService->create($validatedData);

        return redirect()->route('posts.index');
    }

    public function show($id)
    {
        $post = $this->postService->getById($id);

        return view('posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = $this->postService->getById($id);

        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, $id)
    {        
        $post = $this->postService->update($id, $request->all());

        return response()->json([
            'status' => true,
            'message' => 'Post Edited Successfully',
            'data' => $post,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->postService->delete($id);

        return response()->json([
            'status' => true,
            'message' => 'Post Deleted Successfully',
        ], Response::HTTP_OK);    }
}
