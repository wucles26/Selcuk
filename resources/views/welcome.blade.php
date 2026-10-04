<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        :root {
            --bg: #f3f6f4;
            --ink: #14231c;
            --muted: #5d6f66;
            --accent: #0f6b4c;
            --line: #d7e2dc;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, #dff3ea 0%, transparent 42%),
                var(--bg);
            padding: 1.5rem;
        }
        .card {
            width: min(440px, 100%);
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 2rem;
            box-shadow: 0 18px 40px rgba(20, 35, 28, 0.06);
        }
        h1 {
            margin: 0 0 0.5rem;
            font-size: 1.8rem;
            letter-spacing: -0.03em;
        }
        p {
            margin: 0 0 1.5rem;
            color: var(--muted);
            line-height: 1.5;
        }
        .actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-secondary {
            background: #fff;
            color: var(--ink);
            border: 1px solid var(--line);
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>{{ config('app.name', 'Laravel') }}</h1>
        <p>Tek domain üzerinde multi-tenant uygulama. Organizasyonunuzu oluşturun veya giriş yapın.</p>
        <div class="actions">
            @auth
                <a class="btn btn-primary" href="/app">Dashboard</a>
                <a class="btn btn-secondary" href="/admin">Admin paneli</a>
                <form method="POST" action="/app/logout">
                    @csrf
                    <button class="btn btn-secondary" type="submit">Çıkış</button>
                </form>
            @else
                <a class="btn btn-primary" href="/login">Giriş yap</a>
                <a class="btn btn-secondary" href="/register">Kayıt ol</a>
                <a class="btn btn-secondary" href="/admin">Admin paneli</a>
            @endauth
        </div>
    </div>
</body>
</html>
