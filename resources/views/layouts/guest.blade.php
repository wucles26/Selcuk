<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <style>
        :root {
            --bg: #f4f7f5;
            --surface: #ffffff;
            --ink: #14231c;
            --muted: #5d6f66;
            --line: #d7e2dc;
            --accent: #0f6b4c;
            --accent-soft: #e6f4ee;
            --danger: #b42318;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, #dff3ea 0%, transparent 40%),
                linear-gradient(180deg, #eef5f1 0%, var(--bg) 45%, #e8efe9 100%);
        }
        a { color: var(--accent); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .shell {
            width: min(440px, calc(100% - 2rem));
            margin: 4rem auto;
        }
        .brand {
            display: block;
            margin-bottom: 1.25rem;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--ink);
            text-decoration: none;
        }
        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 1.75rem;
            box-shadow: 0 18px 40px rgba(20, 35, 28, 0.06);
        }
        h1 {
            margin: 0 0 0.35rem;
            font-size: 1.55rem;
            letter-spacing: -0.03em;
        }
        .subtitle {
            margin: 0 0 1.5rem;
            color: var(--muted);
            font-size: 0.95rem;
        }
        label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .field { margin-bottom: 1rem; }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0.75rem 0.85rem;
            background: #fbfffc;
            color: var(--ink);
        }
        input:focus {
            outline: 2px solid color-mix(in srgb, var(--accent) 35%, white);
            border-color: var(--accent);
        }
        .hint {
            margin-top: 0.35rem;
            color: var(--muted);
            font-size: 0.8rem;
        }
        .error {
            margin: 0.35rem 0 0;
            color: var(--danger);
            font-size: 0.82rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-top: 0.5rem;
            border: 0;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            background: var(--accent);
            color: white;
            font-weight: 600;
            cursor: pointer;
        }
        .btn:hover { filter: brightness(1.05); }
        .footer-link {
            margin-top: 1.25rem;
            text-align: center;
            color: var(--muted);
            font-size: 0.9rem;
        }
        .remember {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin: 0.75rem 0 0.25rem;
            color: var(--muted);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="shell">
        <a class="brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
        <div class="panel">
            @yield('content')
        </div>
    </div>
</body>
</html>
