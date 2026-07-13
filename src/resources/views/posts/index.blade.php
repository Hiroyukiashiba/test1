@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-0">投稿一覧</h1>
            <p class="text-muted mb-0 mt-1">{{ $posts->count() }} 件の投稿</p>
        </div>
    </div>

    @forelse ($posts as $post)
        <div class="post-card d-flex {{ $post->is_completed ? 'completed' : '' }}">
            <div class="card-accent"></div>
            <div class="card-inner d-flex justify-content-between align-items-center">
                <div style="flex:1; min-width:0;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h5 class="card-title mb-0">{{ $post->title }}</h5>
                        @if ($post->is_completed)
                            <span class="badge-done"><i class="bi bi-check2 me-1"></i>完了</span>
                        @else
                            <span class="badge-todo">未完了</span>
                        @endif
                    </div>
                    <p class="card-text mb-0">{{ Str::limit($post->body, 80) }}</p>
                </div>
                <div class="d-flex gap-2 ms-3 flex-shrink-0">
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-glow btn-primary-grad btn-sm">
                        <i class="bi bi-eye me-1"></i>詳細
                    </a>
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-pencil me-1"></i>編集
                    </a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-glow btn-danger-grad btn-sm"
                            onclick="return confirm('削除しますか？')">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p class="fs-5 fw-semibold mb-1">投稿がありません</p>
            <p class="text-muted">「新規作成」ボタンから最初の投稿を作成しましょう。</p>
        </div>
    @endforelse
@endsection
