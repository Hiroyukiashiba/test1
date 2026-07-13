<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel MVC チュートリアル</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #6c63ff;
            --primary-dark: #574fd6;
            --accent: #ff6584;
            --success: #2ecc71;
            --bg: #f0f2f8;
        }

        body {
            background: var(--bg);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        /* ナビバー */
        .navbar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            padding: 1rem 0;
        }
        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #a78bfa, #f9a8d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .nav-btn {
            background: linear-gradient(135deg, var(--primary), #a78bfa);
            border: none;
            border-radius: 25px;
            padding: 0.4rem 1.2rem;
            color: white;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(108,99,255,0.4);
        }
        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108,99,255,0.6);
            color: white;
        }

        /* メインコンテナ */
        .main-container {
            max-width: 860px;
            margin: 2.5rem auto;
            padding: 0 1rem;
        }

        /* ページタイトル */
        .page-title {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1a1a2e, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.2rem;
        }

        /* アラート */
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border: none;
            border-left: 4px solid var(--success);
            border-radius: 12px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(46,204,113,0.15);
        }

        /* カード */
        .post-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            margin-bottom: 1.2rem;
            transition: all 0.3s;
            overflow: hidden;
        }
        .post-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 35px rgba(108,99,255,0.15);
        }
        .post-card.completed {
            opacity: 0.75;
            background: #f8f9fa;
        }
        .post-card .card-accent {
            width: 5px;
            background: linear-gradient(180deg, var(--primary), #a78bfa);
            border-radius: 5px 0 0 5px;
            flex-shrink: 0;
        }
        .post-card.completed .card-accent {
            background: linear-gradient(180deg, var(--success), #a8e6cf);
        }
        .card-inner {
            padding: 1.4rem 1.6rem;
            flex: 1;
        }
        .post-card .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.4rem;
        }
        .post-card.completed .card-title {
            text-decoration: line-through;
            color: #999;
        }
        .post-card .card-text {
            font-size: 0.92rem;
            color: #666;
        }

        /* バッジ */
        .badge-done {
            background: linear-gradient(135deg, var(--success), #a8e6cf);
            color: white;
            border-radius: 20px;
            padding: 0.3rem 0.9rem;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .badge-todo {
            background: linear-gradient(135deg, #e0e0e0, #bdbdbd);
            color: #555;
            border-radius: 20px;
            padding: 0.3rem 0.9rem;
            font-size: 0.78rem;
            font-weight: 600;
        }

        /* ボタン共通 */
        .btn-glow {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            transition: all 0.3s;
            border: none;
        }
        .btn-primary-grad {
            background: linear-gradient(135deg, var(--primary), #a78bfa);
            color: white;
            box-shadow: 0 4px 15px rgba(108,99,255,0.35);
        }
        .btn-primary-grad:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108,99,255,0.5);
            color: white;
        }
        .btn-success-grad {
            background: linear-gradient(135deg, #2ecc71, #1abc9c);
            color: white;
            box-shadow: 0 4px 15px rgba(46,204,113,0.35);
        }
        .btn-success-grad:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(46,204,113,0.5);
            color: white;
        }
        .btn-undo-grad {
            background: linear-gradient(135deg, #bdbdbd, #9e9e9e);
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .btn-undo-grad:hover {
            transform: translateY(-2px);
            color: white;
        }
        .btn-danger-grad {
            background: linear-gradient(135deg, var(--accent), #e53935);
            color: white;
            box-shadow: 0 4px 15px rgba(255,101,132,0.35);
        }
        .btn-danger-grad:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255,101,132,0.5);
            color: white;
        }
        .btn-ghost {
            background: transparent;
            border: 2px solid #ddd;
            color: #555;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            transition: all 0.3s;
        }
        .btn-ghost:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* フォーム */
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        }
        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 0.4rem;
        }
        .form-control {
            border: 2px solid #e8e8f0;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            transition: all 0.3s;
            font-size: 0.97rem;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(108,99,255,0.12);
        }

        /* 詳細カード */
        .detail-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        }
        .detail-body {
            background: #f7f8fc;
            border-radius: 12px;
            padding: 1.5rem;
            white-space: pre-wrap;
            color: #333;
            line-height: 1.8;
            font-size: 1rem;
            border-left: 4px solid var(--primary);
        }
        .detail-meta {
            color: #999;
            font-size: 0.85rem;
        }

        /* 空状態 */
        .empty-state {
            text-align: center;
            padding: 4rem 0;
            color: #bbb;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('posts.index') }}">
                <i class="bi bi-lightning-charge-fill me-2" style="-webkit-text-fill-color: #a78bfa;"></i>
                Laravel MVC Tutorial
            </a>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('api-demo') }}" class="nav-btn" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                    <i class="bi bi-braces me-1"></i> API Demo
                </a>
                <a href="{{ route('posts.create') }}" class="nav-btn">
                    <i class="bi bi-plus-lg me-1"></i> 新規作成
                </a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
