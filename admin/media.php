<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
requireAuth();

define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('UPLOAD_URL', '/assets/uploads/');
define('MAX_UPLOAD_BYTES', 5 * 1024 * 1024);
const ALLOWED_MIME = [
  'image/jpeg' => 'jpg',
  'image/png'  => 'png',
  'image/webp' => 'webp',
  'image/gif'  => 'gif',
];

$media = readJson('media.json');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
  requireCsrf();
  $file = $_FILES['image'];

  if ($file['error'] !== UPLOAD_ERR_OK) {
    $errors[] = 'Nahrání souboru se nezdařilo.';
  } elseif ($file['size'] > MAX_UPLOAD_BYTES) {
    $errors[] = 'Soubor je příliš velký (max 5 MB).';
  } else {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset(ALLOWED_MIME[$mime])) {
      $errors[] = 'Nepovolený typ souboru. Povoleny jsou JPG, PNG, WebP a GIF.';
    } elseif (!@getimagesize($file['tmp_name'])) {
      $errors[] = 'Soubor není platný obrázek.';
    } else {
      $ext  = ALLOWED_MIME[$mime];
      $base = slugify(pathinfo($file['name'], PATHINFO_FILENAME)) ?: 'obrazek';
      $name = $base . '-' . substr(bin2hex(random_bytes(4)), 0, 8) . '.' . $ext;
      while (file_exists(UPLOAD_DIR . $name)) {
        $name = $base . '-' . substr(bin2hex(random_bytes(4)), 0, 8) . '.' . $ext;
      }
      if (move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $name)) {
        $media[] = [
          'id'       => nextId($media),
          'file'     => $name,
          'url'      => UPLOAD_URL . $name,
          'alt'      => trim($_POST['alt'] ?? ''),
          'uploaded' => date('c'),
        ];
        writeJson('media.json', $media);
        flash('Obrázek byl nahrán.');
      } else {
        $errors[] = 'Soubor se nepodařilo uložit na server.';
      }
    }
  }

  if (!empty($errors)) {
    flash(implode(' ', $errors), 'error');
  }
  header('Location: /admin/media.php');
  exit;
}

if (isset($_POST['delete_id'])) {
  requireCsrf();
  $delId = (int)$_POST['delete_id'];
  $item = null;
  foreach ($media as $m) { if ($m['id'] === $delId) { $item = $m; break; } }
  if ($item) {
    $path = UPLOAD_DIR . basename($item['file']);
    if (is_file($path)) @unlink($path);
    $media = array_values(array_filter($media, fn($m) => $m['id'] !== $delId));
    writeJson('media.json', $media);
    flash('Obrázek byl smazán.');
  }
  header('Location: /admin/media.php');
  exit;
}

adminHeader('Média', 'media');
?>

<div class="card" style="margin-bottom:1.5rem;">
  <div class="card__title">Nahrát nový obrázek</div>
  <form method="POST" enctype="multipart/form-data">
    <?= csrfField() ?>
    <div class="form-row">
      <div class="form-group">
        <label for="image">Soubor (JPG, PNG, WebP, GIF, max 5 MB)</label>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif" required>
      </div>
      <div class="form-group">
        <label for="alt">Alternativní text</label>
        <input type="text" id="alt" name="alt" placeholder="Popis obrázku pro přístupnost a SEO">
      </div>
    </div>
    <button type="submit" class="btn btn--primary">Nahrát</button>
  </form>
</div>

<div class="card">
  <div class="card__title">Knihovna médií (<?= count($media) ?>)</div>
  <p class="form-hint" style="margin-bottom:1rem;">Nový obrázek zde nahrajte a poté zkopírovanou cestu vložte tam, kde ho chcete použít (např. do loga v <code>index.php</code>).</p>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;">
    <?php foreach (array_reverse($media) as $m): ?>
      <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;">
        <img src="<?= htmlspecialchars($m['url']) ?>" alt="<?= htmlspecialchars($m['alt']) ?>" style="width:100%;height:110px;object-fit:cover;display:block;">
        <div style="padding:.5rem;font-size:.75rem;">
          <div style="word-break:break-all;color:var(--muted);margin-bottom:.4rem;"><?= htmlspecialchars($m['file']) ?></div>
          <div style="display:flex;gap:.4rem;">
            <button type="button" class="btn btn--outline btn--sm" style="flex:1;" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($m['url'], ENT_QUOTES) ?>')">Kopírovat cestu</button>
          </div>
          <form method="POST" onsubmit="return confirm('Opravdu smazat tento obrázek? Tuto akci nelze vrátit zpět.');" style="margin-top:.4rem;">
            <?= csrfField() ?>
            <input type="hidden" name="delete_id" value="<?= $m['id'] ?>">
            <button type="submit" class="btn btn--sm" style="width:100%;color:#b02a37;border:1px solid #b02a37;background:#fff;">Smazat</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if (empty($media)): ?>
      <p style="color:var(--muted);">Zatím nebyly nahrány žádné obrázky.</p>
    <?php endif; ?>
  </div>
</div>

<?php adminFooter(); ?>
