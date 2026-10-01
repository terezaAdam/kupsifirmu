<?php
require_once __DIR__ . '/includes/auth.php';

// Only a POST with a valid CSRF token logs out, so a link or image on another
// site cannot sign the admin out.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrfVerify()) {
  header('Location: /admin/index.php');
  exit;
}
logout();
header('Location: /prihlaseni');
exit;
