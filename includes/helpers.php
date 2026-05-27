<?php

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirectTo($path) {
    header("Location: $path");
    exit;
}

function currentUser() {
    return $_SESSION['user'] ?? null;
}

function requireAuth() {
    if (!currentUser()) {
        redirectTo('index.php');
    }

    return currentUser();
}

function requireGuest() {
    if (currentUser()) {
        redirectTo('dashboard.php');
    }
}

function renderHead($title) {
    ?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= e($title) ?></title>
        <link rel="stylesheet" href="assets/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    </head>
    <?php
}

function renderNavbar($options = []) {
    $logoHref = $options['logoHref'] ?? 'dashboard.php';
    $logoIsLink = $options['logoIsLink'] ?? true;
    $center = $options['center'] ?? '';
    $right = $options['right'] ?? '';
    ?>
    <nav class="navbar">
        <div class="nav-inner">
            <div class="nav-left">
                <?php if ($logoIsLink): ?>
                    <a href="<?= e($logoHref) ?>" class="nav-logo">☕ Brew & Bean</a>
                <?php else: ?>
                    <span class="nav-logo">☕ Brew & Bean</span>
                <?php endif; ?>
            </div>
            <div class="nav-center"><?= $center ?></div>
            <div class="nav-right"><?= $right ?></div>
        </div>
    </nav>
    <?php
}
