<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/prihlaseni' || $uri === '/prihlaseni/') {
  require __DIR__ . '/admin/login.php';
  return true;
}

return false;
