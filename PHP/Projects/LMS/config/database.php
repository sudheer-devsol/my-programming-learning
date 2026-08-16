<?php
// ------------------------------------------------------------
// Database connection settings
// Change these values to match your local XAMPP / MySQL setup
// ------------------------------------------------------------
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "lms_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
