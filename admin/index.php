<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
requireAuth();

$content = ksContent();
$media   = readJson('media.json');

adminHeader('Dashboard', 'dashboard');
?>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-card__num"><?= count($content['why_items'] ?? []) ?></div>
    <div class="stat-card__label">Karty „Proč my“</div>
  </div>
  <div class="stat-card">
    <div class="stat-card__num"><?= count($content['steps_items'] ?? []) ?></div>
    <div class="stat-card__label">Kroky převodu</div>
  </div>
  <div class="stat-card">
    <div class="stat-card__num"><?= count($content['faq_items'] ?? []) ?></div>
    <div class="stat-card__label">Otázky FAQ</div>
  </div>
  <div class="stat-card">
    <div class="stat-card__num"><?= count($media) ?></div>
    <div class="stat-card__label">Obrázky v médiích</div>
  </div>
</div>

<div class="card">
  <div class="card__title">Rychlé akce</div>
  <div style="display:flex;flex-wrap:wrap;gap:.75rem;">
    <a href="/admin/content.php" class="btn btn--primary">Upravit texty na webu</a>
    <a href="/admin/media.php" class="btn btn--outline">Nahrát obrázek</a>
    <a href="/" target="_blank" class="btn btn--outline">Zobrazit web →</a>
  </div>
</div>

<?php adminFooter(); ?>
