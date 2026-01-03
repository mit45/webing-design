<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($meta_title ?? 'Webing Store') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="/">Anasayfa</a> | <a href="/?r=admin">Admin</a>
        </nav>
    </header>
    <main>
        <?php require $viewFile; ?>
    </main>
    <footer>
        <small>&copy; <?= date('Y') ?> Webing Store</small>
    </footer>
</body>
</html>
