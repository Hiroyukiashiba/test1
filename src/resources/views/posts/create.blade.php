@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="page-title">新規投稿</h1>
        <p class="text-muted">新しい投稿を作成します。</p>
    </div>

    <div class="form-card">
        <form action="{{ route('posts.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="title" class="form-label">
                    <i class="bi bi-type-bold me-1" style="color: var(--primary);"></i> タイトル
                </label>
                <input type="text" id="title" name="title"
                    class="form-control @error('title') is-invalid @enderror"
                    placeholder="タイトルを入力してください"
                    value="{{ old('title') }}">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="body" class="form-label">
                    <i class="bi bi-body-text me-1" style="color: var(--primary);"></i> 本文
                </label>
                <textarea id="body" name="body" rows="8"
                    class="form-control @error('body') is-invalid @enderror"
                    placeholder="本文を入力してください">{{ old('body') }}</textarea>
                @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-glow btn-primary-grad">
                    <i class="bi bi-floppy-fill me-2"></i>保存する
                </button>
                <a href="{{ route('posts.index') }}" class="btn btn-ghost">
                    <i class="bi bi-x-lg me-2"></i>キャンセル
                </a>
            </div>
        </form>
    </div>
@endsection
