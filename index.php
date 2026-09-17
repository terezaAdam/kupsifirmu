<?php
$pageTitle = 'Kup si firmu – Hotové firmy, okamžité podnikání';
$pageDesc  = 'Kupte si nově založenou ready-made společnost s.r.o. a fakturujte během několika hodin. Bez čekání, bez dluhů, plně splacený kapitál.';
require __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="container">
    <p class="hero__label"><?= htmlspecialchars($c['hero_label']) ?></p>
    <h1 class="hero__title"><?= $c['hero_title'] ?></h1>
    <p class="hero__desc"><?= htmlspecialchars($c['hero_desc']) ?></p>
    <div class="hero__cta">
      <a href="#kontakt" class="btn btn--primary"><?= htmlspecialchars($c['hero_cta_primary']) ?></a>
      <a href="#proc" class="btn btn--outline-white"><?= htmlspecialchars($c['hero_cta_secondary']) ?></a>
    </div>
  </div>
</section>


<section class="section" id="proc">
  <div class="container">
    <p class="section-label"><?= htmlspecialchars($c['why_label']) ?></p>
    <h2 class="section-title"><?= htmlspecialchars($c['why_title']) ?></h2>
    <div class="divider"></div>
    <p class="section-lead"><?= htmlspecialchars($c['why_lead']) ?></p>

    <div class="why-grid">
      <?php foreach ($c['why_items'] as $i => $item): ?>
      <div class="why-card fade-in">
        <div class="why-card__num"><?= sprintf('%02d', $i + 1) ?></div>
        <div class="why-card__title"><?= htmlspecialchars($item['title']) ?></div>
        <p><?= htmlspecialchars($item['text']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<section class="section section--alt" id="postup">
  <div class="container">
    <p class="section-label"><?= htmlspecialchars($c['steps_label']) ?></p>
    <h2 class="section-title"><?= htmlspecialchars($c['steps_title']) ?></h2>
    <div class="divider"></div>
    <p class="section-lead"><?= htmlspecialchars($c['steps_lead']) ?></p>

    <div class="steps">
      <?php foreach ($c['steps_items'] as $i => $item): ?>
      <div class="step fade-in">
        <div class="step__circle"><?= $i + 1 ?></div>
        <div class="step__title"><?= htmlspecialchars($item['title']) ?></div>
        <p><?= htmlspecialchars($item['text']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<section class="section" id="zahrnuto">
  <div class="container">
    <p class="section-label"><?= htmlspecialchars($c['included_label']) ?></p>
    <h2 class="section-title"><?= htmlspecialchars($c['included_title']) ?></h2>
    <div class="divider"></div>

    <div class="included">
      <?php foreach ($c['included_items'] as $item): ?>
      <div class="included__item fade-in"><span class="included__check">✓</span> <?= htmlspecialchars($item) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<section class="section section--alt" id="faq">
  <div class="container">
    <p class="section-label"><?= htmlspecialchars($c['faq_label']) ?></p>
    <h2 class="section-title"><?= htmlspecialchars($c['faq_title']) ?></h2>
    <div class="divider"></div>

    <div class="faq" data-faq>
      <?php foreach ($c['faq_items'] as $item): ?>
      <div class="faq-item">
        <button class="faq-item__q" type="button"><?= htmlspecialchars($item['q']) ?> <span class="faq-item__icon">+</span></button>
        <div class="faq-item__a"><p><?= htmlspecialchars($item['a']) ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<section class="section" id="kontakt">
  <div class="container">
    <p class="section-label"><?= htmlspecialchars($c['contact_label']) ?></p>
    <h2 class="section-title"><?= htmlspecialchars($c['contact_title']) ?></h2>
    <div class="divider"></div>
    <p class="section-lead"><?= htmlspecialchars($c['contact_lead']) ?></p>

    <form class="contact-wrap" id="contact-form" novalidate>
      <input type="hidden" name="access_key" value="87132ecf-2d72-448e-948e-b0ce152ff0ee">
      <input type="hidden" name="subject" value="Nová poptávka z webu kupsifirmu.cz">
      <input type="hidden" name="from_name" value="Web KUP SI FIRMU">
      <input type="text" name="botcheck" id="botcheck" autocomplete="off" tabindex="-1" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;">
      <div class="form-row">
        <label for="name">Jméno a příjmení</label>
        <input type="text" id="name" name="name" required>
      </div>
      <div class="form-row">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-row">
        <label for="phone">Telefon</label>
        <input type="tel" id="phone" name="phone">
      </div>
      <div class="form-row">
        <label for="message">Poznámka / Představa o firmě</label>
        <textarea id="message" name="message"></textarea>
      </div>
      <button type="submit" class="btn btn--primary btn--block">Odeslat nezávaznou poptávku</button>
      <p class="form-msg" id="form-msg"></p>
    </form>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
