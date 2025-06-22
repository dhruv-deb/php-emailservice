<?php
require_once __DIR__ . '/functions.php';
session_start();

$step = 1;
$email = $_POST['email'] ?? '';
$inputCode = $_POST['verification_code'] ?? '';

if (isset($_POST['submit-email']) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['pending_email'] = $email;
    $code = generateVerificationCode();
    $_SESSION['verification_code'] = $code;
    sendVerificationEmail($email, $code);
    $step = 2;
} elseif (isset($_POST['submit-verification'])) {
    if (isset($_SESSION['pending_email'], $_SESSION['verification_code']) &&
        $_SESSION['verification_code'] === $inputCode) {
        registerEmail($_SESSION['pending_email']);
        $email = $_SESSION['pending_email'];
        $step = 3;
        unset($_SESSION['pending_email'], $_SESSION['verification_code']);
    } else {
        $step = 2;
        $error = 'Invalid code, please try again.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>GitHub Timeline</title>
</head>
<body>
    <h1>Subscribe to GitHub Timeline Updates</h1>

    <?php if ($step === 1): ?>
        <form method="POST">
            <label>
                Enter your email:
                <input type="email" name="email" required value="<?= htmlspecialchars($email) ?>">
            </label>
            <button type="submit" name="submit-email" id="submit-email">Submit</button>
        </form>

    <?php elseif ($step === 2): ?>
        <p>A verification code has been sent to <strong><?= htmlspecialchars($_SESSION['pending_email']) ?></strong>.</p>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label>
                Enter verification code:
                <input type="text" name="verification_code" maxlength="6" required>
            </label>
            <button type="submit" name="submit-verification" id="submit-verification">Verify</button>
        </form>

    <?php elseif ($step === 3): ?>
        <p>Your email (<?= htmlspecialchars($email) ?>) has been successfully verified and registered!</p>
    <?php endif; ?>
</body>
</html>

