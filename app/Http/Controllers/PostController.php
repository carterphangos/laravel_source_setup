<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use App\Enums\BaseLimit;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $posts = Cache::get('posts');
        if ($posts) {
            return response()->json([
                'status' => true,
                'message' => 'All Posts Get From Cache Successfully',
                'data' => $posts,
            ], Response::HTTP_OK);
        }

        $posts = $this->postService->getAllPosts(
            $request->input('perPage', BaseLimit::LIMIT_10),
            $request->except('perPage')
        );

        Cache::put('posts', $posts, now()->addMinutes(5));

        return response()->json([
            'status' => true,
            'message' => 'All Posts Get Successfully',
            'data' => $posts,
        ], Response::HTTP_OK);
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

        return response()->json([
            'status' => true,
            'message' => 'Post Get Successfully',
            'data' => $post,
        ], Response::HTTP_OK);
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

        $this->syncCache();

        return response()->json([
            'status' => true,
            'message' => 'Post Edited Successfully',
            'data' => $post,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->postService->delete($id);

        $this->syncCache();

        return response()->json([
            'status' => true,
            'message' => 'Post Deleted Successfully',
        ], Response::HTTP_OK);
    }

    private function syncCache()
    {
        $posts = $this->postService->getAllPosts(BaseLimit::LIMIT_10);
        Cache::put('posts', $posts, now()->addMinutes(5));
    }
}
