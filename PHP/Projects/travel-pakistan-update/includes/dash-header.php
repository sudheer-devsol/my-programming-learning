<?php

if(!isset($dash_role)){ $dash_role = "user"; }
$asset_prefix = "../";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title." | Travel Pakistan" : "Dashboard | Travel Pakistan" ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $asset_prefix ?>assets/css/style.css">
    <?php include "theme-loader.php"; ?>
</head>
<body>

<nav class="tp-navbar py-2">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="<?= $asset_prefix ?>index.php">
            <span class="mark"><i class="bi bi-signpost-split-fill"></i></span>
            Travel Pakistan
        </a>
        <div class="d-flex align-items-center gap-3">
            <a href="<?= $asset_prefix ?>index.php" class="btn btn-ghost btn-sm"><i class="bi bi-globe2"></i> View Site</a>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <!-- <img src="https://i.pravatar.cc/80?img=<?= $dash_role=='admin' ? 68 : 32 ?>" style="width:36px;height:36px;border-radius:50%;object-fit:cover;"> -->
                    <?php
                $profile_image = !empty($_SESSION["user_image"])
                    ? $_SESSION["user_image"]
                    : "default-user.png";
                ?>

                <img src="<?= $asset_prefix ?>assets/images/users/<?= htmlspecialchars($profile_image); ?>"
                    alt="Profile"
                    style="width:36px;height:36px;border-radius:50%;object-fit:cover;">                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">
                        <?= htmlspecialchars($_SESSION["first_name"]) . " " . htmlspecialchars($_SESSION["last_name"]); ?>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <?php if($dash_role == "user"): ?>
                        <li>
                            <a class="dropdown-item" href="profile.php">
                                <i class="bi bi-person me-2"></i>My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="theme.php">
                                <i class="bi bi-palette me-2"></i>Theme Settings
                            </a>
                        </li>
                           <?php else: ?>
                        <li>
                            <a class="dropdown-item" href="theme.php">
                                <i class="bi bi-palette me-2"></i>Theme Settings
                            </a>
                        </li>
                            <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item text-danger" href="../logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>

                    </ul>
                </div>
        </div>
    </div>
</nav>
