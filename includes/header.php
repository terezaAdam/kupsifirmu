<?php
require_once __DIR__ . '/data.php';
$c = ksContent();
?>
<!doctype html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle ?? 'Kup si firmu – Hotové firmy, okamžité podnikání') ?></title>
<meta name="description" content="<?= htmlspecialchars($pageDesc ?? 'Kupte si nově založenou ready-made společnost s.r.o. a fakturujte během několika hodin. Bez čekání, bez dluhů, plně splacený kapitál.') ?>">
<link rel="icon" href="/assets/img/fav.kup.png">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="nav" id="nav">
  <div class="nav__inner">
    <a href="/" class="nav__logo">
      <img src="/assets/img/logo.png" alt="Kup si firmu" class="nav__logo-img">
    </a>
    <nav class="nav__links">
      <a href="/#proc" class="nav__link">Proč my</a>
      <a href="/#postup" class="nav__link">Postup</a>
      <a href="/#zahrnuto" class="nav__link">Co je v ceně</a>
      <a href="/#faq" class="nav__link">FAQ</a>
    </nav>
    <div class="nav__cta">
      <a href="/#kontakt" class="btn btn--primary btn--sm"><?= htmlspecialchars($c['nav_cta']) ?></a>
      <button class="nav__hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
  <nav class="nav__mobile" id="nav-mobile">
    <a href="/#proc" class="nav__link">Proč my</a>
    <a href="/#postup" class="nav__link">Postup</a>
    <a href="/#zahrnuto" class="nav__link">Co je v ceně</a>
    <a href="/#faq" class="nav__link">FAQ</a>
  </nav>
</header>
