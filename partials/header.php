<?php $flash = getFlash(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Inventaris') ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="index.php">CRUD Inventaris</a>
        <nav>
            <a href="index.php">Produk</a>
            <a href="tambah.php">Tambah Produk</a>
        </nav>
    </div>
</header>
<main class="container page">
<?php if ($flash): ?>
    <div class="alert alert--<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
<?php endif; ?>
