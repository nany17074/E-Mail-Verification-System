<?php
require_once 'functions.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['unsubscribe_email'])) {
        $email = filter_input(INPUT_POST, 'unsubscribe_email', FILTER_VALIDATE_EMAIL);
        if ($email) {
            if (!isEmailRegistered($email)) {
                $message = 'Email not found.';
            } else {
                $code = generateVerificationCode();
                if (sendVerificationEmail($email, $code, 'unsubscribe')) {
                    $_SESSION['unsubscribe_email'] = $email;
                    $_SESSION['unsubscribe_code'] = $code;
                    $message = 'Verification code sent for unsubscription.';
                } else {
                    $message = 'Failed to send verification email.';
                }
            }
        } else {
            $message = 'Invalid email address.';
        }
    } elseif (isset($_POST['verification_code'])) {
        $code = $_POST['verification_code'];
        $email = $_SESSION['unsubscribe_email'] ?? '';

        if ($_SESSION['unsubscribe_code'] === $code) {
            if (unsubscribeEmail($email)) {
                $message = 'Unsubscribed successfully!';
            } else {
                $message = 'Email not found.';
            }
        } else {
            $message = 'Invalid verification code.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Unsubscribe from XKCD</title>
</head>

<body>
    <h1>Unsubscribe from XKCD Comics</h1>
    <form method="post">
        <input type="email" name="unsubscribe_email" required placeholder="Enter your email">
        <button id="submit-unsubscribe" type="submit">Unsubscribe</button>
    </form>
    <?php if (!empty($_SESSION['unsubscribe_email'])): ?>
        <form method="post">
            <input type="text" name="verification_code" maxlength="6" required placeholder="Enter verification code">
            <button id="submit-verification" type="submit">Verify</button>
        </form>
    <?php endif; ?>
    <p><?= htmlspecialchars($message) ?></p>
</body>

</html>