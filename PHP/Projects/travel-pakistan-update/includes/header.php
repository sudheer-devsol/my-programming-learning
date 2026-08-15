<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($active_page))
{
    $active_page = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . " | Travel Pakistan" : "Travel Pakistan — Discover Every Province" ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php include "theme-loader.php"; ?>
</head>
<body>

<nav class="navbar navbar-expand-lg tp-navbar py-2">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            <span class="mark">
                <i class="bi bi-signpost-split-fill"></i>
            </span>
            Travel Pakistan
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="mainNav">

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link <?= $active_page=="home" ? "active" : "" ?>" href="index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $active_page=="provinces" ? "active" : "" ?>" href="provinces.php">
                        Provinces
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $active_page=="categories" ? "active" : "" ?>" href="categories.php">
                        Categories
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $active_page=="posts" ? "active" : "" ?>" href="posts.php">
                        Latest Posts
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $active_page=="about" ? "active" : "" ?>" href="about.php">
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $active_page=="contact" ? "active" : "" ?>" href="contact.php">
                        Contact
                    </a>
                </li>

            </ul>

            <div class="d-flex gap-2 align-items-center">

                <?php if(isset($_SESSION["user_id"])): ?>

                    <?php if($_SESSION["role_id"] == 1): ?>

                        <a href="admin/dashboard.php" class="btn btn-ghost btn-nav-cta btn-sm">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>

                    <?php else: ?>

                        <a href="user/dashboard.php" class="btn btn-ghost btn-nav-cta btn-sm">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>

                    <?php endif; ?>

                    <a href="logout.php" class="btn btn-teal btn-nav-cta btn-sm">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </a>

                <?php else: ?>

                    <a href="login.php" class="btn btn-ghost btn-nav-cta btn-sm">
                        Log In
                    </a>

                    <a href="register.php" class="btn btn-teal btn-nav-cta btn-sm">
                        Register
                    </a>

                <?php endif; ?>

            </div>

        </div>

    </div>
</nav>