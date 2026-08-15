<?php
$base = '';
require_once 'includes/init.php';

// Clear the "remember me" cookie and token from the database
if (isLoggedIn()) {
    $userModel->clearRememberToken($_SESSION['user_id']);
}
clearRememberCookie();

// Destroy the session completely (Topic 7: Sessions)
$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;
?>
