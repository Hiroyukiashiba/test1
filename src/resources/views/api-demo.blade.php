@extends('layouts.app')

@section('content')
<style>
    .demo-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: auto auto;
        gap: 1.5rem;
    }
    .panel {
        background: white;
        border-radius: 16px;
        padding: 1.6rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .endpoint-badge {
        font-family: monospace;
        font-size: 0.78rem;
        background: #f0f2f8;
        border: 1px solid #dde;
        border-radius: 6px;
        padding: 0.2rem 0.6rem;
        color: #555;
    }
    /* ログエリア */
    .log-panel {
        grid-column: 1 / -1;
    }
    .log-area {
        background: #0f172a;
        border-radius: 12px;
        padding: 1rem 1.2rem;
        height: 280px;
        overflow-y: auto;
        font-family: 'Courier New', monospace;
        font-size: 0.82rem;
        color: #94a3b8;
    }
    .log-entry { margin-bottom: 0.8rem; border-bottom: 1px solid #1e293b; padding-bottom: 0.8rem; }
    .log-entry:last-child { border-bottom: none; }
    .log-req  { color: #60a5fa; }
    .log-res  { color: #34d399; }
    .log-err  { color: #f87171; }
    .log-time { color: #64748b; font-size: 0.75rem; }
    /* 投稿カード */
    .post-item {
        background: #f7f8fc;
        border-radius: 10px;
        padding: 0.9rem 1rem;
        margin-bottom: 0.7rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.2s;
    }
    .post-item:hover { box-shadow: 0 2px 10px rgba(108,99,255,0.1); }
    .post-item.done { opacity: 0.6; }
    .post-item.done .post-item-title { text-decoration: line-through; color: #999; }
    .post-item-title { font-weight: 600; font-size: 0.93rem; flex: 1; }
    .post-item-body  { font-size: 0.82rem; color: #777; flex: 1; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
    .posts-list { max-height: 360px; overflow-y: auto; }
    /* ボタン */
    .btn-api {
        border: none;
        border-radius: 8px;
        padding: 0.4rem 0.9rem;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-api:hover { transform: translateY(-1px); }
    .btn-fetch  { background: linear-gradient(135deg, #6c63ff, #a78bfa); color: white; }
    .btn-done   { background: linear-gradient(135deg, #2ecc71, #1abc9c); color: white; }
    .btn-edit   { background: linear-gradient(135deg, #f59e0b, #f97316); color: white; }
    .btn-del    { background: linear-gradient(135deg, #ff6584, #e53935); color: white; }
    .btn-submit { background: linear-gradient(135deg, #6c63ff, #a78bfa); color: white; width: 100%; padding: 0.65rem; font-size: 0.93rem; border-radius: 10px; border: none; font-weight: 700; cursor: pointer; transition: all 0.3s; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(108,99,255,0.4); }
    /* 編集パネル */
    .edit-panel { display: none; grid-column: 1 / -1; border: 2px solid #fde68a; }
    .edit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
    .request-preview {
        background: #0f172a;
        border-radius: 12px;
        padding: 1.2rem;
        font-family: 'Courier New', monospace;
        font-size: 0.83rem;
    }
    .request-preview .label { color: #64748b; font-size: 0.78rem; margin-bottom: 0.6rem; }
    .request-preview .method-line { color: #f59e0b; margin-bottom: 0.4rem; }
    .request-preview pre { color: #34d399; margin: 0; white-space: pre-wrap; word-break: break-all; }
    /* フォーム */
    .api-input {
        width: 100%;
        border: 2px solid #e8e8f0;
        border-radius: 8px;
        padding: 0.6rem 0.8rem;
        font-size: 0.92rem;
        margin-bottom: 0.8rem;
        outline: none;
        transition: border-color 0.2s;
    }
    .api-input:focus { border-color: #6c63ff; }
    /* 説明テーブル */
    .route-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .route-table th { background: #f0f2f8; padding: 0.5rem 0.8rem; text-align: left; font-weight: 700; color: #444; }
    .route-table td { padding: 0.5rem 0.8rem; border-bottom: 1px solid #f0f2f8; }
    .method { font-family: monospace; font-weight: 700; font-size: 0.78rem; border-radius: 4px; padding: 0.15rem 0.5rem; }
    .method-get    { background: #dbeafe; color: #1d4ed8; }
    .method-post   { background: #dcfce7; color: #166534; }
    .method-patch  { background: #fef3c7; color: #92400e; }
    .method-delete { background: #fee2e2; color: #991b1b; }
    .uri { font-family: monospace; color: #555; }
</style>

<div class="mb-4">
    <h1 class="page-title">API Demo</h1>
    <p class="text-muted">JavaScript の <code>fetch()</code> を使って Laravel API を操作します。</p>
</div>

{{-- API ルート一覧 --}}
<div class="panel mb-4">
    <div class="panel-title"><i class="bi bi-map" style="color:#6c63ff;"></i> 利用可能な API エンドポイント</div>
    <table class="route-table">
        <tr><th>メソッド</th><th>URI</th><th>説明</th></tr>
        <tr><td><span class="method method-get">GET</span></td><td class="uri">/api/posts</td><td>投稿一覧を取得</td></tr>
        <tr><td><span class="method method-post">POST</span></td><td class="uri">/api/posts</td><td>新規投稿を作成</td></tr>
        <tr><td><span class="method method-get">GET</span></td><td class="uri">/api/posts/{id}</td><td>投稿1件を取得</td></tr>
        <tr><td><span class="method method-patch">PATCH</span></td><td class="uri">/api/posts/{id}</td><td>投稿を更新</td></tr>
        <tr><td><span class="method method-delete">DELETE</span></td><td class="uri">/api/posts/{id}</td><td>投稿を削除</td></tr>
        <tr><td><span class="method method-patch">PATCH</span></td><td class="uri">/api/posts/{id}/complete</td><td>完了状態をトグル</td></tr>
    </table>
</div>

<div class="demo-grid">

    {{-- 投稿一覧パネル --}}
    <div class="panel">
        <div class="panel-title">
            <i class="bi bi-list-ul" style="color:#6c63ff;"></i> 投稿一覧
            <span class="endpoint-badge">GET /api/posts</span>
            <button class="btn-api btn-fetch ms-auto" onclick="fetchPosts()">
                <i class="bi bi-arrow-clockwise me-1"></i>取得
            </button>
        </div>
        <div class="posts-list" id="posts-list">
            <p class="text-muted text-center py-3" style="font-size:0.9rem;">「取得」ボタンを押してください</p>
        </div>
    </div>

    {{-- 新規作成パネル --}}
    <div class="panel">
        <div class="panel-title">
            <i class="bi bi-plus-circle" style="color:#2ecc71;"></i> 新規投稿を作成
            <span class="endpoint-badge">POST /api/posts</span>
        </div>
        <form onsubmit="createPost(event)">
            <input class="api-input" type="text" id="new-title" placeholder="タイトル（必須）" required>
            <textarea class="api-input" id="new-body" rows="5" placeholder="本文（必須）" required style="resize:vertical;"></textarea>
            <button type="submit" class="btn-submit">
                <i class="bi bi-send-fill me-2"></i>作成する
            </button>
        </form>
    </div>

    {{-- 編集パネル --}}
    <div class="panel edit-panel" id="edit-panel">
        <div class="panel-title">
            <i class="bi bi-pencil-fill" style="color:#f59e0b;"></i> 投稿を編集
            <span class="endpoint-badge" id="edit-endpoint-badge">PATCH /api/posts/{id}</span>
            <button class="btn-api ms-auto" style="background:#e5e7eb; color:#555;" onclick="cancelEdit()">
                <i class="bi bi-x-lg me-1"></i>キャンセル
            </button>
        </div>
        <form onsubmit="updatePost(event)">
            <input type="hidden" id="edit-id">
            <div class="edit-grid">
                <div>
                    <input class="api-input" type="text" id="edit-title" placeholder="タイトル（必須）" required
                        oninput="updatePreview()">
                    <textarea class="api-input" id="edit-body" rows="5" placeholder="本文（必須）" required
                        style="resize:vertical;" oninput="updatePreview()"></textarea>
                    <div style="display:flex; gap:0.8rem;">
                        <button type="submit" class="btn-submit" style="background:linear-gradient(135deg,#f59e0b,#f97316); width:auto; padding:0.65rem 2rem;">
                            <i class="bi bi-floppy-fill me-2"></i>保存する
                        </button>
                        <button type="button" class="btn-api" style="background:#e5e7eb; color:#555; padding:0.5rem 1.2rem;" onclick="cancelEdit()">
                            キャンセル
                        </button>
                    </div>
                </div>
                <div class="request-preview">
                    <div class="label">送信されるリクエスト（リアルタイム）</div>
                    <div class="method-line" id="preview-method">PATCH /api/posts/{id}</div>
                    <div class="label mt-2">Content-Type: application/json</div>
                    <pre id="edit-preview">{}</pre>
                </div>
            </div>
        </form>
    </div>

    {{-- API ログパネル --}}
    <div class="panel log-panel">
        <div class="panel-title d-flex align-items-center">
            <i class="bi bi-terminal" style="color:#f59e0b;"></i> API ログ
            <span class="text-muted ms-2" style="font-size:0.8rem; font-weight:400;">リクエスト / レスポンスの内容がここに表示されます</span>
            <button class="btn-api ms-auto" style="background:#1e293b; color:#94a3b8; font-size:0.78rem;" onclick="clearLog()">
                クリア
            </button>
        </div>
        <div class="log-area" id="log-area">
            <div class="log-entry">
                <div class="log-time">-- ここにリクエスト・レスポンスが表示されます --</div>
            </div>
        </div>
    </div>

</div>

<script>
// ---- ユーティリティ ----

function now() {
    return new Date().toLocaleTimeString('ja-JP');
}

// req: "GET /api/posts"  status: "200 OK"  json: レスポンスオブジェクト（省略可）
function log(req, status, json = null, isError = false) {
    const area = document.getElementById('log-area');
    const entry = document.createElement('div');
    entry.className = 'log-entry';
    const jsonHtml = json !== null
        ? `<pre class="${isError ? 'log-err' : 'log-res'}" style="margin:0.4rem 0 0; white-space:pre-wrap; word-break:break-all;">${escHtml(JSON.stringify(json, null, 2))}</pre>`
        : '';
    entry.innerHTML = `
        <div class="log-time">${now()}</div>
        <div class="log-req">▶ リクエスト: ${req}</div>
        <div class="${isError ? 'log-err' : 'log-res'}">◀ ステータス: ${status}</div>
        ${jsonHtml}
    `;
    area.prepend(entry);
}

function clearLog() {
    document.getElementById('log-area').innerHTML =
        '<div class="log-entry"><div class="log-time">-- ログをクリアしました --</div></div>';
}

// ---- API 呼び出し ----

// 一覧取得
async function fetchPosts() {
    const endpoint = 'GET /api/posts';
    try {
        const res = await fetch('/api/posts');
        const data = await res.json();
        log(endpoint, `200 OK（${data.length} 件）`, data);
        renderPosts(data);
    } catch (e) {
        log(endpoint, e.message, null, true);
    }
}

// 投稿作成
async function createPost(e) {
    e.preventDefault();
    const title = document.getElementById('new-title').value;
    const body  = document.getElementById('new-body').value;
    const endpoint = 'POST /api/posts';

    try {
        const res = await fetch('/api/posts', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ title, body }),
        });
        const data = await res.json();

        if (!res.ok) {
            log(endpoint, `${res.status} エラー`, data, true);
            return;
        }

        log(endpoint, '201 Created', data);
        document.getElementById('new-title').value = '';
        document.getElementById('new-body').value  = '';
        fetchPosts();
    } catch (e) {
        log(endpoint, e.message, null, true);
    }
}

// 完了トグル
async function toggleComplete(id, currentState) {
    const endpoint = `PATCH /api/posts/${id}/complete`;
    try {
        const res = await fetch(`/api/posts/${id}/complete`, {
            method: 'PATCH',
            headers: { 'Accept': 'application/json' },
        });
        const data = await res.json();
        log(endpoint, '200 OK', data);
        fetchPosts();
    } catch (e) {
        log(endpoint, e.message, null, true);
    }
}

// 削除
async function deletePost(id, title) {
    if (!confirm(`「${title}」を削除しますか？`)) return;
    const endpoint = `DELETE /api/posts/${id}`;
    try {
        const res = await fetch(`/api/posts/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' },
        });
        log(endpoint, '204 No Content（レスポンスボディなし）');
        fetchPosts();
    } catch (e) {
        log(endpoint, e.message, null, true);
    }
}

// 編集パネルを開く
function showEdit(id, title, body) {
    document.getElementById('edit-id').value    = id;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-body').value  = body;
    document.getElementById('edit-endpoint-badge').textContent = `PATCH /api/posts/${id}`;
    document.getElementById('preview-method').textContent      = `PATCH /api/posts/${id}`;

    updatePreview();

    const panel = document.getElementById('edit-panel');
    panel.style.display = 'block';
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// 編集パネルを閉じる
function cancelEdit() {
    document.getElementById('edit-panel').style.display = 'none';
}

// リクエストボディのプレビューを更新
function updatePreview() {
    const title = document.getElementById('edit-title').value;
    const body  = document.getElementById('edit-body').value;
    document.getElementById('edit-preview').textContent =
        JSON.stringify({ title, body }, null, 2);
}

// 投稿更新（PATCH /api/posts/{id}）
async function updatePost(e) {
    e.preventDefault();
    const id    = document.getElementById('edit-id').value;
    const title = document.getElementById('edit-title').value;
    const body  = document.getElementById('edit-body').value;
    const endpoint = `PATCH /api/posts/${id}`;

    try {
        const res = await fetch(`/api/posts/${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ title, body }),
        });
        const data = await res.json();

        if (!res.ok) {
            log(endpoint, `${res.status} エラー`, data, true);
            return;
        }

        log(endpoint, '200 OK', data);
        cancelEdit();
        fetchPosts();
    } catch (e) {
        log(endpoint, e.message, null, true);
    }
}

// ---- 描画 ----

function renderPosts(posts) {
    const list = document.getElementById('posts-list');

    if (posts.length === 0) {
        list.innerHTML = '<p class="text-muted text-center py-3" style="font-size:0.9rem;">投稿がありません</p>';
        return;
    }

    list.innerHTML = posts.map(post => `
        <div class="post-item ${post.is_completed ? 'done' : ''}">
            <div style="flex:1; min-width:0;">
                <div class="post-item-title">${escHtml(post.title)}</div>
                <div class="post-item-body">${escHtml(post.body)}</div>
            </div>
            <button class="btn-api btn-done" onclick="toggleComplete(${post.id}, ${post.is_completed})"
                title="${post.is_completed ? '未完了に戻す' : '完了にする'}">
                <i class="bi bi-${post.is_completed ? 'arrow-counterclockwise' : 'check-circle'}"></i>
            </button>
            <button class="btn-api btn-edit" onclick="showEdit(${post.id}, '${escJs(post.title)}', '${escJs(post.body)}')"
                title="編集">
                <i class="bi bi-pencil"></i>
            </button>
            <button class="btn-api btn-del" onclick="deletePost(${post.id}, '${escJs(post.title)}')"
                title="削除">
                <i class="bi bi-trash3"></i>
            </button>
        </div>
    `).join('');
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function escJs(str) {
    return String(str).replace(/'/g, "\\'");
}

// 初期ロード
fetchPosts();
</script>
@endsection
