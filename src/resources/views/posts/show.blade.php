@extends('layouts.app')

@section('content')
    <div class="detail-card">
        {{-- ヘッダー --}}
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    @if ($post->is_completed)
                        <span class="badge-done fs-6"><i class="bi bi-check-circle-fill me-1"></i>完了済み</span>
                    @else
                        <span class="badge-todo fs-6"><i class="bi bi-circle me-1"></i>未完了</span>
                    @endif
                </div>
                <h1 class="page-title">{{ $post->title }}</h1>
                <p class="detail-meta">
                    <i class="bi bi-clock me-1"></i>作成日: {{ $post->created_at->format('Y年m月d日 H:i') }}
                    @if ($post->updated_at != $post->created_at)
                        &nbsp;｜&nbsp;<i class="bi bi-pencil me-1"></i>更新: {{ $post->updated_at->format('Y年m月d日 H:i') }}
                    @endif
                </p>
            </div>
        </div>

        {{-- 本文 --}}
        <div class="detail-body mb-5">{{ $post->body }}</div>

        {{-- アクションボタン --}}
        <div class="d-flex flex-wrap gap-3">

            {{-- タスク完了ボタン --}}
            <form action="{{ route('posts.complete', $post) }}" method="POST">
                @csrf
                @method('PATCH')
                @if ($post->is_completed)
                    <button type="submit" class="btn btn-glow btn-undo-grad">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>未完了に戻す
                    </button>
                @else
                    <button type="submit" class="btn btn-glow btn-success-grad">
                        <i class="bi bi-check-circle-fill me-2"></i>タスク完了
                    </button>
                @endif
            </form>

            <a href="{{ route('posts.edit', $post) }}" class="btn btn-glow btn-primary-grad">
                <i class="bi bi-pencil-fill me-2"></i>編集する
            </a>

            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-glow btn-danger-grad"
                    onclick="return confirm('この投稿を削除しますか？')">
                    <i class="bi bi-trash3-fill me-2"></i>削除
                </button>
            </form>

            <a href="{{ route('posts.index') }}" class="btn btn-ghost">
                <i class="bi bi-arrow-left me-2"></i>一覧へ戻る
            </a>
        </div>
    </div>
@endsection
