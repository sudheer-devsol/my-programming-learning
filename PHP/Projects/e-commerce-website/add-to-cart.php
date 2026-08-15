<?php
$base = '';
require_once 'includes/init.php';

$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$isAjax = isset($_POST['ajax']);

$product = $productModel->getById($productId);

if (!$product) {
    if ($isAjax) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    setFlash('error', 'Product not found.');
    header('Location: products.php');
    exit;
}

// The cart is a simple associative array stored in the SESSION (Topic 9: Arrays)
$cart = getCart();

if (isset($cart[$productId])) {
    $cart[$productId]['quantity'] += $quantity;
} else {
    $cart[$productId] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $productModel->finalPrice($product),
        'image' => $product['image'],
        'quantity' => $quantity
    ];
}

$_SESSION['cart'] = $cart;

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'cart_count' => cartCount()]);
    exit;
}

setFlash('success', 'Product added to cart!');
header('Location: cart.php');
exit;
?>
