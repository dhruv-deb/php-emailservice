<?php
require_once __DIR__ . '/functions.php';
session_start();

$step = 1;
$email = $_POST['unsubscribe_email'] ?? '';
$inputCode = $_POST['unsubscribe_verification_code'] ?? '';
$error = '';

if (isset($_POST['submit-unsubscribe']) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['unsubscribe_email'] = $email;
    $code = generateVerificationCode();
    $_SESSION['unsubscribe_code'] = $code;
    sendVerificationEmail($email, $code,'unsubscribe');
    $step = 2;
} elseif (isset($_POST['verify-unsubscribe'])) {
    if (isset($_SESSION['unsubscribe_code'], $_SESSION['unsubscribe_email']) &&
        $_SESSION['unsubscribe_code'] === $inputCode) {
        
        unsubscribeEmail($_SESSION['unsubscribe_email']);
        $email = $_SESSION['unsubscribe_email'];
        $step = 3;
        unset($_SESSION['unsubscribe_email'], $_SESSION['unsubscribe_code']);
    } else {
        $step = 2;
        $error = "Invalid verification code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Unsubscribe</title>
</head>
<body>
    <h1>Unsubscribe from GitHub Timeline Updates</h1>

    <?php if ($step === 1): ?>
        <form method="POST">
            <label>
                Enter your email:
                <input type="email" name="unsubscribe_email" required>
            </label>
            <button type="submit" name="submit-unsubscribe" id="submit-unsubscribe">Unsubscribe</button>
        </form>

    <?php elseif ($step === 2): ?>
        <p>A confirmation code has been sent to <strong><?= htmlspecialchars($_SESSION['unsubscribe_email']) ?></strong>.</p>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label>
                Enter verification code:
                <input type="text" name="unsubscribe_verification_code" required>
            </label>
            <button type="submit" name="verify-unsubscribe" id="verify-unsubscribe">Verify</button>
        </form>

    <?php elseif ($step === 3): ?>
        <p><strong><?= htmlspecialchars($email) ?></strong> has been unsubscribed successfully.</p>
    <?php endif; ?>
</body>
</html>
