<?php
$base = '';
require_once 'includes/init.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$cart = getCart();
if (isset($cart[$productId])) {
    unset($cart[$productId]);
    $_SESSION['cart'] = $cart;
}

setFlash('success', 'Item removed from cart.');
header('Location: cart.php');
exit;
?>
