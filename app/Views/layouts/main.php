<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? config('name')) ?></title>
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body>
    <div class="shell">
        <header class="nav">
            <a class="brand" href="<?= e(url('/')) ?>"><?= e(config('name')) ?></a>
            <nav>
                <a href="<?= e(url('/')) ?>">Ana sayfa</a>
                <a href="<?= e(url('/about')) ?>">Hakkında</a>
            </nav>
        </header>
        <main class="card">
            <?= $content ?>
        </main>
    </div>
</body>
</html>
