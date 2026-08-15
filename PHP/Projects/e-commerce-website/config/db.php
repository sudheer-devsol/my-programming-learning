<?php
/*
    config/db.php
    ---------------------------------------------
    This file connects our website to the MySQL database.
    We use mysqli (procedural style) because it is the
    easiest way for beginners to understand.
*/

// 1. Database settings (change these to match your computer/server)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // XAMPP default password is empty
define('DB_NAME', 'simpleshop');

// 2. Create the connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 3. Check connection and show a friendly error if it fails
if (!$conn) {
    // Topic: Dealing with Errors, Warnings and Notices
    die("Database connection failed: " . mysqli_connect_error());
}

// 4. Make sure we read/write text correctly (supports emojis, etc.)
mysqli_set_charset($conn, "utf8mb4");
?>
