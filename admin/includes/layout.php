<?php
function adminHeader(string $title, string $activeLink = ''): void {
  $flash = getFlash();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title) ?> – Kup si firmu Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="stylesheet" href="/admin/assets/css/admin.css">
</head>
<body>
<div class="admin-layout">

  <aside class="sidebar" role="navigation" aria-label="Admin menu">
    <div class="sidebar__brand">
      <div class="sidebar__brand-name">KUP SI FIRMU</div>
      <div class="sidebar__brand-sub">Správa obsahu</div>
    </div>
    <nav class="sidebar__nav">
      <div class="sidebar__section">Přehled</div>
      <a href="/admin/index.php" class="sidebar__link <?= $activeLink === 'dashboard' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Dashboard
      </a>

      <div class="sidebar__section">Obsah</div>
      <a href="/admin/content.php" class="sidebar__link <?= $activeLink === 'content' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><line x1="4" y1="9" x2="20" y2="9"/></svg>
        Texty na webu
      </a>
      <a href="/admin/media.php" class="sidebar__link <?= $activeLink === 'media' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
        Média
      </a>

      <div class="sidebar__section">Web</div>
      <a href="/" target="_blank" class="sidebar__link">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        Zobrazit web
      </a>
    </nav>
    <div class="sidebar__footer">
      <a href="/admin/logout.php">Odhlásit se</a>
    </div>
  </aside>

  <div class="main-content">
    <div class="topbar">
      <div class="topbar__title"><?= htmlspecialchars($title) ?></div>
      <div class="topbar__actions">
        <a href="/" target="_blank" class="btn btn--outline btn--sm">
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          Web
        </a>
        <a href="/admin/logout.php" class="btn btn--outline btn--sm">Odhlásit</a>
      </div>
    </div>
    <div class="page">

    <?php if ($flash): ?>
      <div class="alert alert--<?= $flash['type'] === 'success' ? 'success' : 'error' ?>">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>
<?php
}

function adminFooter(): void {
?>
    </div>
  </div>
</div>
</body>
</html>
<?php
}
