<?php
/*
    includes/init.php
    ---------------------------------------------
    Every page on the site starts by including this ONE file.
    It loads the database, helper functions, and OOP classes,
    then creates ready-to-use objects: $db, $productModel, etc.
*/

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Order.php';

// Create one shared Database object and pass it into the other classes.
// (This pattern is called "Dependency Injection" - a fancy name for a simple idea:
//  "give each class the tools it needs instead of it creating them itself".)
$db = new Database($conn);
$productModel = new Product($db);
$userModel = new User($db);
$orderModel = new Order($db);

/* -----------------------------------------------------
   AUTO LOGIN VIA "REMEMBER ME" COOKIE (Topic 20: Cookies)
------------------------------------------------------ */
if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {
    $user = $userModel->findByToken($_COOKIE['remember_token']);
    if ($user) {
        $userModel->login($user);
    }
}
?>
