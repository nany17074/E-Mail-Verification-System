<?php
require_once 'functions.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($email) {
            if (isEmailRegistered($email)) {
                $message = 'Email already registered.';
            } else {
                $code = generateVerificationCode();
                if (sendVerificationEmail($email, $code)) {
                    $_SESSION['email'] = $email;
                    $_SESSION['verification_code'] = $code;
                    $message = 'Verification code sent to your email.';
                } else {
                    $message = 'Failed to send verification email.';
                }
            }
        } else {
            $message = 'Invalid email address.';
        }
    } elseif (isset($_POST['verification_code'])) {
        $code = $_POST['verification_code'];
        $email = $_SESSION['email'] ?? '';

        if (verifyCode($email, $code)) {
            if (registerEmail($email)) {
                $message = 'Email verified and registered successfully!';
            } else {
                $message = 'Email already registered.';
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
    <title>XKCD Subscription</title>
</head>

<body>
    <h1>Subscribe to XKCD Comics</h1>
    <form method="post">
        <input type="email" name="email" required placeholder="Enter your email">
        <button id="submit-email" type="submit">Submit</button>
    </form>
    <form method="post">
        <input type="text" name="verification_code" maxlength="6" required placeholder="Enter verification code">
        <button id="submit-verification" type="submit">Verify</button>
    </form>
    <p><?= htmlspecialchars($message) ?></p>
</body>

</html>