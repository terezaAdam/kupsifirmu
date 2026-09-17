<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
  header('Location: /admin/index.php');
  exit;
}

$error = '';
if (!empty($_GET['timeout'])) {
  $error = 'Byli jste odhlášeni kvůli neaktivitě. Přihlaste se prosím znovu.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrfVerify()) {
    $error = 'Neplatný bezpečnostní token. Zkuste to znovu.';
  } elseif (isLockedOut()) {
    $error = 'Příliš mnoho neúspěšných pokusů. Zkuste to prosím za ' . ceil(LOGIN_LOCKOUT_SECONDS / 60) . ' minut.';
  } else {
    $pw = (string)($_POST['password'] ?? '');
    if (login($pw)) {
      header('Location: /admin/index.php');
      exit;
    }
    $error = 'Nesprávné heslo. Zkuste to znovu.';
  }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Přihlášení – Kup si firmu Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="stylesheet" href="/admin/assets/css/admin.css">
</head>
<body>
<div class="login-page">
  <div class="login-box">
    <div class="login-logo">KUP SI FIRMU</div>
    <div class="login-sub">Správa obsahu webu</div>

    <?php if ($error): ?>
      <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <?= csrfField() ?>
      <div class="form-group">
        <label for="pw">Heslo</label>
        <input type="password" id="pw" name="password" required autofocus autocomplete="current-password" placeholder="••••••••">
      </div>
      <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;margin-top:.5rem;">Přihlásit se</button>
    </form>

    <div style="margin-top:1.5rem;text-align:center;">
      <a href="/" style="font-size:.8rem;color:var(--muted);">← Zpět na web</a>
    </div>
  </div>
</div>
</body>
</html>
