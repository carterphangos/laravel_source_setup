<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\Request;

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

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $this->postService->update($id, $validatedData);

        return redirect()->route('posts.index');
    }

    public function destroy($id)
    {
        $this->postService->delete($id);

        return redirect()->route('posts.index');
    }
}
