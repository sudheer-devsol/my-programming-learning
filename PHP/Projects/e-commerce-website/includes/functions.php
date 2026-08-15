<?php
/*
    includes/functions.php
    ---------------------------------------------
    A collection of small, easy-to-read helper functions
    used everywhere on the site. Keeping them in one file
    means we write each piece of logic only once (DRY).
*/

// Always start the session at the very top of every page that needs it.
// We check first so we never call session_start() twice (that causes a warning).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* -----------------------------------------------------
   STRING FUNCTIONS
------------------------------------------------------ */

// Clean any text coming from a form before we show it or save it.
// Prevents basic HTML/JS injection (XSS) - uses trim(), htmlspecialchars()
function clean($text) {
    $text = trim($text);
    $text = stripslashes($text);
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    return $text;
}

// Turn "Wireless Headphones" into "wireless-headphones" for URLs
function makeSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9]+/', '-', $string);
    return trim($string, '-');
}

// Shorten a product description for listing cards
function shortText($text, $limit = 80) {
    if (strlen($text) <= $limit) {
        return $text;
    }
    return substr($text, 0, $limit) . '...';
}

// Format a number as money, e.g. 1999.5 -> $1,999.50
function money($amount) {
    return '$' . number_format((float)$amount, 2);
}

/* -----------------------------------------------------
   TIME / DATE FUNCTIONS
------------------------------------------------------ */

// Turns a MySQL datetime into something readable, e.g. "10 Jul 2026, 3:45 PM"
function formatDate($datetime) {
    $timestamp = strtotime($datetime);
    return date('d M Y, g:i A', $timestamp);
}

// "2 hours ago" style text - used in the chat box
function timeAgo($datetime) {
    $seconds = time() - strtotime($datetime);

    if ($seconds < 60)   return "just now";
    if ($seconds < 3600) return floor($seconds / 60) . " min ago";
    if ($seconds < 86400) return floor($seconds / 3600) . " hr ago";
    return floor($seconds / 86400) . " day(s) ago";
}

// Generates a random order tracking code like SS-2026-8X4K9P
function generateTrackingCode() {
    $year = date('Y');
    $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
    return "SS-{$year}-{$random}";
}

/* -----------------------------------------------------
   SESSION HELPERS  (Topic 7: Sessions)
------------------------------------------------------ */

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Send the visitor to the login page if they are not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ../login.php');
        exit;
    }
}

// Simple one-time flash message system using sessions
// Example: setFlash('success', 'Order placed!'); then showFlash();
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function showFlash() {
    if (!empty($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $msg  = $_SESSION['flash']['message'];
        echo "<div class='alert alert-$type'>$msg</div>";
        unset($_SESSION['flash']);
    }
}

/* -----------------------------------------------------
   COOKIE HELPERS  (Topic 20: Cookies)
------------------------------------------------------ */

// "Remember me" cookie - stores a random token (NOT the password) for 30 days
function setRememberCookie($token) {
    $expire = time() + (30 * 24 * 60 * 60); // 30 days
    setcookie('remember_token', $token, $expire, '/');
}

function clearRememberCookie() {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Remembers which currency/theme a guest picked, using a simple cookie
function setPreference($key, $value) {
    setcookie('pref_' . $key, $value, time() + (86400 * 90), '/'); // 90 days
}

function getPreference($key, $default = null) {
    $cookieName = 'pref_' . $key;
    return isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : $default;
}

/* -----------------------------------------------------
   CART HELPERS (cart is stored in the SESSION as an array)
------------------------------------------------------ */

function getCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Topic 9: Arrays
    }
    return $_SESSION['cart'];
}

function cartCount() {
    $cart = getCart();
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['quantity'];
    }
    return $total;
}

function cartTotal() {
    $cart = getCart();
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

/* -----------------------------------------------------
   VALIDATION HELPERS (Topic 13: Form Validation)
------------------------------------------------------ */

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidPhone($phone) {
    // Only digits, spaces, +, - allowed, length between 7 and 20
    return preg_match('/^[0-9\-\+\s]{7,20}$/', $phone);
}

/* -----------------------------------------------------
   EMAIL HELPER (Topic 24: Sending Email)
   ---------------------------------------------
   Uses PHPMailer + Gmail SMTP (see config/mail.php) so emails
   actually get delivered - PHP's plain mail() function usually
   doesn't work on localhost/XAMPP without extra server setup.
------------------------------------------------------ */

function sendEmailNotification($to, $subject, $htmlMessage) {
    // Load the 3 PHPMailer files manually (no Composer needed - beginner friendly)
    require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';
    require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
    require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';
    require_once __DIR__ . '/../config/mail.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true); // true = throw exceptions on error

    try {
        // ---- Server settings ----
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_ENCRYPTION === 'ssl'
            ? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
            : PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;
        $mail->Timeout    = 10; // seconds - don't let a slow/unreachable mail server freeze the page

        // ---- Sender / recipient ----
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($to);

        // ---- Content ----
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlMessage;
        $mail->AltBody  = strip_tags($htmlMessage); // plain-text fallback for old email clients

        $mail->send();
        return true;

    } catch (\Exception $e) {
        // Topic 22: Dealing with Errors - never let a failed email crash checkout.
        // We just log it quietly and let the rest of the site keep working.
        error_log(
            date('Y-m-d H:i:s') . " | Email to $to failed: " . $mail->ErrorInfo . PHP_EOL,
            3,
            __DIR__ . '/../error_log.txt'
        );
        return false;
    }
}

/* -----------------------------------------------------
   ERROR / NOTICE HELPER (Topic 22)
------------------------------------------------------ */

// A very simple custom error handler that logs problems instead of
// showing scary PHP errors to visitors.
function simpleErrorHandler($errno, $errstr, $errfile, $errline) {
    $logMessage = date('Y-m-d H:i:s') . " | Error [$errno]: $errstr in $errfile on line $errline" . PHP_EOL;
    error_log($logMessage, 3, __DIR__ . '/../error_log.txt');
    // Don't show raw PHP errors to normal visitors:
    return true;
}
set_error_handler('simpleErrorHandler');
?>
