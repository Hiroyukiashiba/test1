<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // 投稿一覧
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    // 投稿作成フォーム
    public function create()
    {
        return view('posts.create');
    }

    // 投稿保存
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100',
            'body'  => 'required',
        ]);

        Post::create($request->only('title', 'body'));

        return redirect()->route('posts.index')->with('success', '投稿を作成しました。');
    }

    // 投稿詳細
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // 投稿編集フォーム
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // 投稿更新
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:100',
            'body'  => 'required',
        ]);

        $post->update($request->only('title', 'body'));

        return redirect()->route('posts.index')->with('success', '投稿を更新しました。');
    }

    // 投稿削除
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')->with('success', '投稿を削除しました。');
    }

    // タスク完了トグル
    public function complete(Post $post)
    {
        $post->update(['is_completed' => !$post->is_completed]);

        $message = $post->is_completed ? 'タスクを完了しました！' : 'タスクを未完了に戻しました。';
        return redirect()->route('posts.show', $post)->with('success', $message);
    }
}
