<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    <style>
        :root {
            --bg: #f3f6f4;
            --surface: #ffffff;
            --ink: #14231c;
            --muted: #5d6f66;
            --line: #d7e2dc;
            --accent: #0f6b4c;
            --accent-soft: #e6f4ee;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top right, #dff3ea 0%, transparent 35%),
                var(--bg);
        }
        a { color: inherit; text-decoration: none; }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--line);
            background: rgba(255,255,255,0.86);
            backdrop-filter: blur(8px);
        }
        .brand { font-weight: 700; letter-spacing: -0.02em; }
        .topbar-meta {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            color: var(--muted);
            font-size: 0.9rem;
        }
        .badge {
            display: inline-flex;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 600;
        }
        .btn-link {
            border: 1px solid var(--line);
            background: white;
            border-radius: 8px;
            padding: 0.45rem 0.75rem;
            cursor: pointer;
            font: inherit;
            color: var(--ink);
        }
        .nav-link {
            color: var(--accent);
            font-weight: 600;
        }
        .content {
            width: min(960px, calc(100% - 2rem));
            margin: 2rem auto;
        }
        .hero {
            margin-bottom: 1.5rem;
        }
        .hero h1 {
            margin: 0 0 0.4rem;
            font-size: clamp(1.6rem, 3vw, 2rem);
            letter-spacing: -0.03em;
        }
        .hero p { margin: 0; color: var(--muted); }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }
        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 1.2rem;
        }
        .card h2 {
            margin: 0 0 0.35rem;
            font-size: 0.85rem;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .card strong {
            display: block;
            font-size: 1.05rem;
            word-break: break-word;
        }
        .button { display:inline-block; border:0; border-radius:8px; padding:.6rem .85rem; background:var(--accent); color:#fff; cursor:pointer; font:inherit; }
        .button.secondary { background:#eef3f0; color:var(--ink); }
        .button.danger { background:#a63535; }
        .notice { padding:.75rem 1rem; border-radius:8px; background:var(--accent-soft); }
        .row { display:flex; justify-content:space-between; gap:1rem; padding:1rem 0; border-bottom:1px solid var(--line); }
        .row small { display:block; color:var(--muted); margin-top:.25rem; }
        .actions { display:flex; gap:.5rem; align-items:center; }
        .form-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; }
        label { display:grid; gap:.35rem; font-weight:600; }
        input, textarea { width:100%; border:1px solid var(--line); border-radius:8px; padding:.7rem; font:inherit; }
        .wide { grid-column:1/-1; }
        .snippet { margin:1.5rem 0; padding:1rem; border:1px solid var(--line); border-radius:8px; display:grid; gap:.35rem; }
        .snippet small, .snippet span { color:#19733f; }
        .snippet p { margin:0; color:var(--muted); }
        @media (max-width:640px) { .form-grid { grid-template-columns:1fr; } .wide { grid-column:auto; } .row { flex-direction:column; } }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}">{{ config('app.name', 'Laravel') }}</a>
        <div class="topbar-meta">
            <a class="nav-link" href="{{ route('news-categories.index') }}">Haber kategorileri</a>
            <a class="nav-link" href="/admin">Admin paneli</a>
            <span class="badge">{{ tenant('id') }}</span>
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="/app/logout">
                @csrf
                <button class="btn-link" type="submit">Çıkış</button>
            </form>
        </div>
    </header>

    <main class="content">
        @yield('content')
    </main>
</body>
@stack('scripts')
</html>
