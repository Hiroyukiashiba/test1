@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="page-title">投稿を編集</h1>
        <p class="text-muted">「{{ $post->title }}」を編集しています。</p>
    </div>

    <div class="form-card">
        <form action="{{ route('posts.update', $post) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="form-label">
                    <i class="bi bi-type-bold me-1" style="color: var(--primary);"></i> タイトル
                </label>
                <input type="text" id="title" name="title"
                    class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title', $post->title) }}">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="body" class="form-label">
                    <i class="bi bi-body-text me-1" style="color: var(--primary);"></i> 本文
                </label>
                <textarea id="body" name="body" rows="8"
                    class="form-control @error('body') is-invalid @enderror">{{ old('body', $post->body) }}</textarea>
                @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-glow btn-primary-grad">
                    <i class="bi bi-floppy-fill me-2"></i>更新する
                </button>
                <a href="{{ route('posts.show', $post) }}" class="btn btn-ghost">
                    <i class="bi bi-x-lg me-2"></i>キャンセル
                </a>
            </div>
        </form>
    </div>
@endsection
