<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateVerificationCode(): string
{
  return strval(random_int(100000, 999999));
}

function isEmailRegistered(string $email): bool
{
  $file = __DIR__ . '/registered_emails.txt';
  if (!file_exists($file)) return false;

  $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  return in_array($email, $emails);
}

function sendVerificationEmail(string $email, string $code, string $type = 'subscribe'): bool
{
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ny0233@srmist.edu.in';
    $mail->Password = 'yapb aihm naau jczp';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('no-reply@example.com', 'XKCD Comics');
    $mail->addAddress($email);
    $mail->isHTML(true);

    if ($type === 'unsubscribe') {
      $mail->Subject = 'Confirm Un-subscription';
      $mail->Body = "<p>To confirm un-subscription, use this code: <strong>$code</strong></p>";
    } else {
      $mail->Subject = 'Your Verification Code';
      $mail->Body = "<p>Your verification code is: <strong>$code</strong></p>";
    }

    return $mail->send();
  } catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
    return false;
  }
}

function registerEmail(string $email): bool
{
  if (isEmailRegistered($email)) return false;

  $file = __DIR__ . '/registered_emails.txt';
  file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
  return true;
}

function unsubscribeEmail(string $email): bool
{
  $file = __DIR__ . '/registered_emails.txt';
  if (!file_exists($file)) {
    // Create empty file if it doesn't exist
    file_put_contents($file, '');
    return false;
  }

  $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if (($key = array_search($email, $emails)) !== false) {
    unset($emails[$key]);
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
    return true;
  }
  return false;
}

function verifyCode(string $email, string $code): bool
{
  session_start();
  return ($_SESSION['verification_code'] ?? '') === $code
    && ($_SESSION['email'] ?? '') === $email;
}

function fetchAndFormatXKCDData(): string
{
  $comicID = random_int(1, 2500);
  $url = "https://xkcd.com/$comicID/info.0.json";
  $json = @file_get_contents($url);

  if (!$json) return '<p>Failed to fetch XKCD comic.</p>';

  $data = json_decode($json, true);
  if (!$data) return '<p>Invalid XKCD data.</p>';

  return sprintf(
    '<h2>XKCD Comic: %s</h2>
        <img src="%s" alt="%s" style="max-width:600px;">
        <p><a href="http://localhost/xkcd-subscription/unsubscribe.php">Unsubscribe</a></p>',
    htmlspecialchars($data['title']),
    htmlspecialchars($data['img']),
    htmlspecialchars($data['alt'])
  );
}

function sendXKCDUpdatesToSubscribers(): void
{
  $file = __DIR__ . '/registered_emails.txt';

  // Create file if it doesn't exist
  if (!file_exists($file)) {
    file_put_contents($file, '');
  }

  // Read emails and check if empty
  $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if (empty($emails)) {
    error_log("No registered emails found. No comics sent.");
    return;
  }

  $comicHTML = fetchAndFormatXKCDData();
  $mail = new PHPMailer(true);
  $emailsSent = 0;

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ny0233@srmist.edu.in';
    $mail->Password = 'yapb aihm naau jczp';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('no-reply@example.com', 'XKCD Comics');
    $mail->isHTML(true);
    $mail->Subject = 'Your XKCD Comic';
    $mail->Body = $comicHTML;

    foreach ($emails as $email) {
      $mail->clearAddresses();
      $mail->addAddress($email);
      if ($mail->send()) {
        $emailsSent++;
      }
    }

    // Only log success if emails were actually sent
    if ($emailsSent > 0) {
      error_log("Comics sent successfully to $emailsSent subscribers at " . date('Y-m-d H:i:s'));
    } else {
      error_log("No comics sent - all emails failed");
    }
  } catch (Exception $e) {
    error_log("Error sending comics: " . $e->getMessage());
  }
}
