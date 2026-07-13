<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // 投稿一覧（GET /api/posts）
    public function index()
    {
        return response()->json(Post::latest()->get());
    }

    // 投稿作成（POST /api/posts）
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'body'  => 'required',
        ]);

        $post = Post::create($validated);

        return response()->json($post, 201);
    }

    // 投稿詳細（GET /api/posts/{post}）
    public function show(Post $post)
    {
        return response()->json($post);
    }

    // 投稿更新（PUT/PATCH /api/posts/{post}）
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'body'  => 'required',
        ]);

        $post->update($validated);

        return response()->json($post);
    }

    // 投稿削除（DELETE /api/posts/{post}）
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }

    // タスク完了トグル（PATCH /api/posts/{post}/complete）
    public function complete(Post $post)
    {
        $post->update(['is_completed' => !$post->is_completed]);

        return response()->json($post);
    }
}
