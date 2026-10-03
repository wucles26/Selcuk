<?php
declare(strict_types=1);

$title = 'Selcuk';
$phpVersion = PHP_VERSION;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        :root {
            color-scheme: light dark;
            --bg: #0f1419;
            --card: #1a2332;
            --text: #e8eef7;
            --muted: #9aa8b8;
            --accent: #5eead4;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, sans-serif;
            background: radial-gradient(circle at top, #1e3a4c 0%, var(--bg) 55%);
            color: var(--text);
            display: grid;
            place-items: center;
            padding: 2rem;
        }
        main {
            width: min(40rem, 100%);
            background: var(--card);
            border: 1px solid rgba(94, 234, 212, 0.18);
            border-radius: 1.25rem;
            padding: 2.25rem;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
        }
        h1 {
            margin: 0 0 0.5rem;
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            letter-spacing: -0.03em;
        }
        p { margin: 0.5rem 0; color: var(--muted); line-height: 1.6; }
        .ok { color: var(--accent); font-weight: 600; }
        code {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 0.9em;
            background: rgba(255, 255, 255, 0.06);
            padding: 0.15em 0.4em;
            border-radius: 0.35rem;
        }
    </style>
</head>
<body>
    <main>
        <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="ok">PHP çalışıyor.</p>
        <p>Bu sayfa Railway üzerinde kök dizindeki <code>index.php</code> dosyasından sunulur.</p>
        <p>PHP sürümü: <code><?= htmlspecialchars($phpVersion, ENT_QUOTES, 'UTF-8') ?></code></p>
    </main>
</body>
</html>
