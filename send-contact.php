<?php
// ── send-contact.php ──
// Zpracování kontaktního formuláře
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'msg' => 'Method not allowed']);
  exit;
}

$name    = trim(strip_tags($_POST['name']    ?? ''));
$email   = trim(strip_tags($_POST['email']   ?? ''));
$phone   = trim(strip_tags($_POST['phone']   ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

if (!$name || !$email) {
  echo json_encode(['ok' => false, 'msg' => 'Vyplňte prosím jméno a e-mail.']);
  exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['ok' => false, 'msg' => 'Neplatná e-mailová adresa.']);
  exit;
}

$to      = 'info@kupsifirmu.cz';
$subject = 'Nová poptávka z webu kupsifirmu.cz';
$headers = implode("\r\n", [
  'From: Web KUP SI FIRMU <noreply@kupsifirmu.cz>',
  'Reply-To: ' . $name . ' <' . $email . '>',
  'Content-Type: text/plain; charset=UTF-8',
  'MIME-Version: 1.0',
]);

$body = "Nová poptávka z webu kupsifirmu.cz\n";
$body .= str_repeat('=', 50) . "\n\n";
$body .= "Jméno:    $name\n";
$body .= "E-mail:   $email\n";
if ($phone) $body .= "Telefon:  $phone\n";
if ($message) $body .= "\nPoznámka:\n$message\n";
$body .= "\n" . str_repeat('-', 50) . "\n";
$body .= "Odesláno: " . date('j. n. Y H:i') . "\n";

$sent = mail($to, $subject, $body, $headers);

echo json_encode(['ok' => $sent]);
