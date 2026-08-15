<?php
$base = '';
require_once 'includes/init.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

$cart = getCart();
if (count($cart) === 0) {
    header('Location: cart.php');
    exit;
}

// --- Basic server-side validation (Topic 13) ---
$address = clean($_POST['address']);
$city = clean($_POST['city']);
$phone = clean($_POST['phone']);
$paymentMethod = in_array($_POST['payment_method'], ['cod', 'card', 'paypal']) ? $_POST['payment_method'] : 'cod';

if (empty($address) || empty($city) || !isValidPhone($phone)) {
    setFlash('error', 'Please fill in a valid shipping address and phone number.');
    header('Location: checkout.php');
    exit;
}

// Extra check for card payment (Topic 22: gracefully handle bad input)
if ($paymentMethod === 'card') {
    $cardNumber = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
    if (strlen($cardNumber) < 12) {
        setFlash('error', 'Please enter a valid card number.');
        header('Location: checkout.php');
        exit;
    }
}

try {
    $total = cartTotal();

    // 1. Create the order
    $order = $orderModel->create($_SESSION['user_id'], $total, $paymentMethod, $address, $city, $phone);

    if (!$order['id']) {
        throw new Exception('Could not save the order. Please try again.');
    }

    // 2. Save each cart item as an order_item, and reduce stock
    foreach ($cart as $item) {
        $orderModel->addItem($order['id'], $item['id'], $item['name'], $item['price'], $item['quantity']);
        $db->run("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?", "iii", [$item['quantity'], $item['id'], $item['quantity']]);
    }

    // 3. Start the tracking history
    $orderModel->addTracking($order['id'], 'placed', 'Your order has been placed successfully.');

    // 4. Send a confirmation email (Topic 24)
    $user = $userModel->findById($_SESSION['user_id']);
    $emailBody = "<h3>Thank you for your order!</h3>
                  <p>Order Tracking Code: <strong>{$order['tracking_code']}</strong></p>
                  <p>Total: " . money($total) . "</p>
                  <p>We will notify you as your order progresses.</p>";
    sendEmailNotification($user['email'], 'Your SimpleShop Order Confirmation', $emailBody);

    // 5. Empty the cart and show a success message
    $_SESSION['cart'] = [];
    $_SESSION['last_tracking_code'] = $order['tracking_code'];

    setFlash('success', 'Order placed successfully! Your tracking code is ' . $order['tracking_code']);
    header('Location: order-tracking.php?code=' . $order['tracking_code']);
    exit;

} catch (Exception $e) {
    // Topic 22: Dealing with Errors gracefully
    error_log($e->getMessage());
    setFlash('error', 'Something went wrong while placing your order. Please try again.');
    header('Location: checkout.php');
    exit;
}
?>
