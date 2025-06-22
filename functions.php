<?php

/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode(): string {
    // TODO: Implement this function
    return str_pad(strval(random_int(0, 999999)), 6, '0', STR_PAD_LEFT);
}

/**
 * Send a verification code to an email.
 */
function sendVerificationEmail(string $email, string $code, string $type = 'register'): bool {
    if ($type === 'unsubscribe') {
        $subject = 'Confirm Unsubscription';
        $message = "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>";
    } else {
        $subject = 'Your Verification Code';
        $message = "<p>Your verification code is: <strong>$code</strong></p>";
    }
    $headers = "From: no-reply@example.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($email, $subject, $message, $headers);
}

/**
 * Register an email by storing it in a file.
 */
function registerEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    if (!in_array($email, $emails)) {
        return file_put_contents($file, $email . PHP_EOL, FILE_APPEND | LOCK_EX) !== false;
    }
    return false;
    // TODO: Implement this function
}

/**
 * Unsubscribe an email by removing it from the list.
 */
function unsubscribeEmail(string $email): bool {
  $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return false;

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $filtered = array_filter($emails, fn($e) => trim($e) !== trim($email));
    
    return file_put_contents($file, implode(PHP_EOL, $filtered) . PHP_EOL, LOCK_EX) !== false;
}

/**
 * Fetch GitHub timeline.
 */
function fetchGitHubTimeline() {
    $url = 'https://api.github.com/events';  
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: GitHubTimelineClient\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    return file_get_contents($url, false, $context);
}

/**
 * Format GitHub timeline data. Returns a valid HTML sting.
 */
function formatGitHubData(array $data): string {
    // TODO: Implement this function
    $html = '<h2>GitHub Timeline Updates</h2>';
    $html .= '<table border="1"><tr><th>Event</th><th>User</th></tr>';

    foreach ($data as $event) {
        $type = htmlspecialchars($event['type'] ?? 'Unknown');
        $user = htmlspecialchars($event['actor']['login'] ?? 'N/A');
        $html .= "<tr><td>$type</td><td>$user</td></tr>";
    }

    $html .= '</table>';
    return $html;
}

/**
 * Send the formatted GitHub updates to registered emails.
 */
function sendGitHubUpdatesToSubscribers(): void {
  $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $json = fetchGitHubTimeline();
    $data = json_decode($json, true);

    if (!is_array($data)) return;
    $html = formatGitHubData($data);

    foreach ($emails as $email) {
        $unsubscribeLink = "http://localhost:8000/unsubscribe.php?email=" . urlencode($email);
        $message = $html . "<p><a href=\"$unsubscribeLink\" id=\"unsubscribe-button\">Unsubscribe</a></p>";
        $subject = "Latest GitHub Updates";
        $headers = "From: no-reply@example.com\r\nContent-Type: text/html;";
        mail($email, $subject, $message, $headers);
    }
    // TODO: Implement this function
}


