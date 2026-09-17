<?php
require_once __DIR__ . '/../../includes/data.php';

ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
if (!empty($_SERVER['HTTPS'])) {
  ini_set('session.cookie_secure', '1');
}
session_start();

$configFile = __DIR__ . '/config.php';
if (file_exists($configFile)) {
  require_once $configFile;
} else {
  http_response_code(500);
  exit('Admin configuration missing.');
}

define('SESSION_IDLE_LIMIT', 30 * 60);
define('LOGIN_MAX_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_SECONDS', 15 * 60);
define('LOGIN_ATTEMPTS_FILE', __DIR__ . '/../../data/.login_attempts.json');

function requireAuth(): void {
  if (empty($_SESSION['ks_admin'])) {
    header('Location: /prihlaseni');
    exit;
  }
  if (!empty($_SESSION['ks_last_activity']) && (time() - $_SESSION['ks_last_activity']) > SESSION_IDLE_LIMIT) {
    logout();
    header('Location: /prihlaseni?timeout=1');
    exit;
  }
  $_SESSION['ks_last_activity'] = time();
}

function isLoggedIn(): bool {
  return !empty($_SESSION['ks_admin']);
}

function clientIp(): string {
  return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

function loginAttempts(): array {
  if (!file_exists(LOGIN_ATTEMPTS_FILE)) return [];
  $data = json_decode(file_get_contents(LOGIN_ATTEMPTS_FILE), true);
  return is_array($data) ? $data : [];
}

function saveLoginAttempts(array $data): void {
  file_put_contents(LOGIN_ATTEMPTS_FILE, json_encode($data));
}

function isLockedOut(): bool {
  $ip = clientIp();
  $all = loginAttempts();
  if (empty($all[$ip])) return false;
  $entry = $all[$ip];
  return $entry['count'] >= LOGIN_MAX_ATTEMPTS && (time() - $entry['last']) < LOGIN_LOCKOUT_SECONDS;
}

function registerFailedLogin(): void {
  $ip = clientIp();
  $all = loginAttempts();
  if (empty($all[$ip]) || (time() - $all[$ip]['last']) > LOGIN_LOCKOUT_SECONDS) {
    $all[$ip] = ['count' => 0, 'last' => time()];
  }
  $all[$ip]['count']++;
  $all[$ip]['last'] = time();
  saveLoginAttempts($all);
  ksLogSecurity("Failed login attempt from $ip (attempt {$all[$ip]['count']})");
}

function clearLoginAttempts(): void {
  $ip = clientIp();
  $all = loginAttempts();
  unset($all[$ip]);
  saveLoginAttempts($all);
}

function login(string $password): bool {
  if (isLockedOut()) return false;
  if (password_verify($password, ADMIN_PASSWORD_HASH)) {
    clearLoginAttempts();
    $_SESSION['ks_admin'] = true;
    $_SESSION['ks_last_activity'] = time();
    session_regenerate_id(true);
    return true;
  }
  registerFailedLogin();
  return false;
}

function logout(): void {
  $_SESSION = [];
  if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
  }
  session_destroy();
}

function csrfToken(): string {
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

function csrfField(): string {
  return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrfToken()) . '">';
}

function csrfVerify(): bool {
  $token = $_POST['csrf_token'] ?? '';
  return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function requireCsrf(): void {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrfVerify()) {
    http_response_code(403);
    die('Neplatný bezpečnostní token. Obnovte stránku a zkuste to znovu.');
  }
}

function ksLogSecurity(string $msg): void {
  $line = '[' . date('Y-m-d H:i:s') . '] ' . $msg . "\n";
  @file_put_contents(__DIR__ . '/../../data/.security.log', $line, FILE_APPEND | LOCK_EX);
}

function readJson(string $file): array { return ksReadJson($file); }
function writeJson(string $file, array $data): bool { return ksWriteJson($file, $data); }

function nextId(array $items): int {
  if (empty($items)) return 1;
  return max(array_column($items, 'id')) + 1;
}

function slugify(string $text): string {
  $text = mb_strtolower($text, 'UTF-8');
  $cs = ['á'=>'a','č'=>'c','ď'=>'d','é'=>'e','ě'=>'e','í'=>'i','ň'=>'n','ó'=>'o','ř'=>'r','š'=>'s','ť'=>'t','ů'=>'u','ú'=>'u','ý'=>'y','ž'=>'z'];
  $text = strtr($text, $cs);
  $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
  $text = preg_replace('/[\s-]+/', '-', trim($text));
  return substr($text, 0, 80);
}

function flash(string $msg, string $type = 'success'): void {
  $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function getFlash(): ?array {
  if (!empty($_SESSION['flash'])) {
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
  }
  return null;
}
