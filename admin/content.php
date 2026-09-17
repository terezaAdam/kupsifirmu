<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
requireAuth();

function postScalar(string $key, string $default = ''): string {
  return trim($_POST[$key] ?? $default);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  requireCsrf();
  $current = ksContent();

  $why = [];
  foreach ((array)($_POST['why_item_title'] ?? []) as $i => $t) {
    $why[] = ['title' => trim($t), 'text' => trim($_POST['why_item_text'][$i] ?? '')];
  }
  $steps = [];
  foreach ((array)($_POST['steps_item_title'] ?? []) as $i => $t) {
    $steps[] = ['title' => trim($t), 'text' => trim($_POST['steps_item_text'][$i] ?? '')];
  }
  $faq = [];
  foreach ((array)($_POST['faq_item_q'] ?? []) as $i => $q) {
    $faq[] = ['q' => trim($q), 'a' => trim($_POST['faq_item_a'][$i] ?? '')];
  }
  $included = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", '', $_POST['included_items'] ?? '')))));

  $data = [
    'nav_cta' => postScalar('nav_cta'),

    'hero_label'          => postScalar('hero_label'),
    'hero_title'          => postScalar('hero_title'),
    'hero_desc'           => postScalar('hero_desc'),
    'hero_cta_primary'    => postScalar('hero_cta_primary'),
    'hero_cta_secondary'  => postScalar('hero_cta_secondary'),

    'why_label' => postScalar('why_label'),
    'why_title' => postScalar('why_title'),
    'why_lead'  => postScalar('why_lead'),
    'why_items' => $why,

    'steps_label' => postScalar('steps_label'),
    'steps_title' => postScalar('steps_title'),
    'steps_lead'  => postScalar('steps_lead'),
    'steps_items' => $steps,

    'included_label' => postScalar('included_label'),
    'included_title' => postScalar('included_title'),
    'included_items'  => $included,

    'faq_label' => postScalar('faq_label'),
    'faq_title' => postScalar('faq_title'),
    'faq_items' => $faq,

    'contact_label' => postScalar('contact_label'),
    'contact_title' => postScalar('contact_title'),
    'contact_lead'  => postScalar('contact_lead'),

    'footer_copy' => postScalar('footer_copy'),
  ];

  writeJson('content.json', $data);
  flash('Texty na webu byly uloženy a jsou ihned vidět.');
  header('Location: /admin/content.php');
  exit;
}

$c = ksContent();
adminHeader('Texty na webu', 'content');
?>

<form method="POST">
  <?= csrfField() ?>

  <div class="card">
    <div class="card__title">Horní menu</div>
    <div class="form-group">
      <label for="nav_cta">Text tlačítka v menu</label>
      <input type="text" id="nav_cta" name="nav_cta" value="<?= htmlspecialchars($c['nav_cta']) ?>">
    </div>
  </div>

  <div class="card">
    <div class="card__title">Úvodní sekce (hero)</div>
    <div class="form-group">
      <label for="hero_label">Krátký popisek nad nadpisem</label>
      <input type="text" id="hero_label" name="hero_label" value="<?= htmlspecialchars($c['hero_label']) ?>">
    </div>
    <div class="form-group">
      <label for="hero_title">Hlavní nadpis</label>
      <input type="text" id="hero_title" name="hero_title" value="<?= htmlspecialchars($c['hero_title']) ?>">
      <p class="form-hint">Část textu lze zvýraznit značkami &lt;em&gt;…&lt;/em&gt;, stejně jako je tomu teď.</p>
    </div>
    <div class="form-group">
      <label for="hero_desc">Úvodní text</label>
      <textarea id="hero_desc" name="hero_desc" rows="3"><?= htmlspecialchars($c['hero_desc']) ?></textarea>
    </div>
    <div class="form-grid">
      <div class="form-group">
        <label for="hero_cta_primary">Text hlavního tlačítka</label>
        <input type="text" id="hero_cta_primary" name="hero_cta_primary" value="<?= htmlspecialchars($c['hero_cta_primary']) ?>">
      </div>
      <div class="form-group">
        <label for="hero_cta_secondary">Text druhého tlačítka</label>
        <input type="text" id="hero_cta_secondary" name="hero_cta_secondary" value="<?= htmlspecialchars($c['hero_cta_secondary']) ?>">
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card__title">Sekce „Proč my“</div>
    <div class="form-grid">
      <div class="form-group">
        <label for="why_label">Popisek</label>
        <input type="text" id="why_label" name="why_label" value="<?= htmlspecialchars($c['why_label']) ?>">
      </div>
      <div class="form-group">
        <label for="why_title">Nadpis</label>
        <input type="text" id="why_title" name="why_title" value="<?= htmlspecialchars($c['why_title']) ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="why_lead">Úvodní text</label>
      <textarea id="why_lead" name="why_lead" rows="2"><?= htmlspecialchars($c['why_lead']) ?></textarea>
    </div>
    <?php foreach ($c['why_items'] as $i => $item): ?>
      <div class="form-grid" style="border-top:1px solid var(--border);padding-top:1rem;margin-top:.5rem;">
        <div class="form-group">
          <label>Karta <?= $i + 1 ?> – titulek</label>
          <input type="text" name="why_item_title[]" value="<?= htmlspecialchars($item['title']) ?>">
        </div>
        <div class="form-group">
          <label>Karta <?= $i + 1 ?> – text</label>
          <input type="text" name="why_item_text[]" value="<?= htmlspecialchars($item['text']) ?>">
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="card">
    <div class="card__title">Sekce „Jak to funguje“</div>
    <div class="form-grid">
      <div class="form-group">
        <label for="steps_label">Popisek</label>
        <input type="text" id="steps_label" name="steps_label" value="<?= htmlspecialchars($c['steps_label']) ?>">
      </div>
      <div class="form-group">
        <label for="steps_title">Nadpis</label>
        <input type="text" id="steps_title" name="steps_title" value="<?= htmlspecialchars($c['steps_title']) ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="steps_lead">Úvodní text</label>
      <textarea id="steps_lead" name="steps_lead" rows="2"><?= htmlspecialchars($c['steps_lead']) ?></textarea>
    </div>
    <?php foreach ($c['steps_items'] as $i => $item): ?>
      <div class="form-grid" style="border-top:1px solid var(--border);padding-top:1rem;margin-top:.5rem;">
        <div class="form-group">
          <label>Krok <?= $i + 1 ?> – titulek</label>
          <input type="text" name="steps_item_title[]" value="<?= htmlspecialchars($item['title']) ?>">
        </div>
        <div class="form-group">
          <label>Krok <?= $i + 1 ?> – text</label>
          <input type="text" name="steps_item_text[]" value="<?= htmlspecialchars($item['text']) ?>">
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="card">
    <div class="card__title">Sekce „Co je v ceně“</div>
    <div class="form-grid">
      <div class="form-group">
        <label for="included_label">Popisek</label>
        <input type="text" id="included_label" name="included_label" value="<?= htmlspecialchars($c['included_label']) ?>">
      </div>
      <div class="form-group">
        <label for="included_title">Nadpis</label>
        <input type="text" id="included_title" name="included_title" value="<?= htmlspecialchars($c['included_title']) ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="included_items">Body v ceně (každý na nový řádek)</label>
      <textarea id="included_items" name="included_items" rows="6"><?= htmlspecialchars(implode("\n", $c['included_items'])) ?></textarea>
    </div>
  </div>

  <div class="card">
    <div class="card__title">Sekce FAQ</div>
    <div class="form-grid">
      <div class="form-group">
        <label for="faq_label">Popisek</label>
        <input type="text" id="faq_label" name="faq_label" value="<?= htmlspecialchars($c['faq_label']) ?>">
      </div>
      <div class="form-group">
        <label for="faq_title">Nadpis</label>
        <input type="text" id="faq_title" name="faq_title" value="<?= htmlspecialchars($c['faq_title']) ?>">
      </div>
    </div>
    <?php foreach ($c['faq_items'] as $i => $item): ?>
      <div style="border-top:1px solid var(--border);padding-top:1rem;margin-top:.5rem;">
        <div class="form-group">
          <label>Otázka <?= $i + 1 ?></label>
          <input type="text" name="faq_item_q[]" value="<?= htmlspecialchars($item['q']) ?>">
        </div>
        <div class="form-group">
          <label>Odpověď <?= $i + 1 ?></label>
          <textarea name="faq_item_a[]" rows="2"><?= htmlspecialchars($item['a']) ?></textarea>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="card">
    <div class="card__title">Sekce kontakt a patička</div>
    <div class="form-grid">
      <div class="form-group">
        <label for="contact_label">Popisek</label>
        <input type="text" id="contact_label" name="contact_label" value="<?= htmlspecialchars($c['contact_label']) ?>">
      </div>
      <div class="form-group">
        <label for="contact_title">Nadpis</label>
        <input type="text" id="contact_title" name="contact_title" value="<?= htmlspecialchars($c['contact_title']) ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="contact_lead">Úvodní text formuláře</label>
      <textarea id="contact_lead" name="contact_lead" rows="2"><?= htmlspecialchars($c['contact_lead']) ?></textarea>
    </div>
    <div class="form-group">
      <label for="footer_copy">Text v patičce (za rokem)</label>
      <input type="text" id="footer_copy" name="footer_copy" value="<?= htmlspecialchars($c['footer_copy']) ?>">
    </div>
  </div>

  <button type="submit" class="btn btn--primary">Uložit a publikovat</button>
</form>

<?php adminFooter(); ?>
