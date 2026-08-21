<?php
require 'config.php';
require 'functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {

    case 'add': {
        $name   = trim($_POST['name'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $phone  = trim($_POST['phone'] ?? '');
        $course = trim($_POST['course'] ?? '');

        if ($name !== '' && $email !== '') {
            $stmt = mysqli_prepare($conn, 'INSERT INTO students (name, email, phone, course) VALUES (?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $phone, $course);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        break;
    }

    case 'update': {
        $id     = (int) ($_POST['id'] ?? 0);
        $name   = trim($_POST['name'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $phone  = trim($_POST['phone'] ?? '');
        $course = trim($_POST['course'] ?? '');

        if ($id > 0 && $name !== '' && $email !== '') {
            $stmt = mysqli_prepare($conn, 'UPDATE students SET name = ?, email = ?, phone = ?, course = ? WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'ssssi', $name, $email, $phone, $course, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        break;
    }

    case 'delete': {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = mysqli_prepare($conn, 'DELETE FROM students WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        break;
    }

    case 'list':
    default:
        // fall through — just re-render the table
        break;
}

// Every action ends the same way: send back the fresh table rows as HTML.
echo renderStudentRows($conn);

mysqli_close($conn);
