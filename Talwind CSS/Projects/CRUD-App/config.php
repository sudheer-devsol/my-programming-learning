<?php
// ---- Database configuration ----
$host   = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'student_db';

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

if (!$conn) {
    http_response_code(500);
    exit('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
