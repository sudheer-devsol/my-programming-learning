<?php
require_once "includes/functions.php";

if (is_logged_in()) {
    redirect_to_dashboard($_SESSION["role"]);
} else {
    header("Location: login.php");
    exit;
}
?>
