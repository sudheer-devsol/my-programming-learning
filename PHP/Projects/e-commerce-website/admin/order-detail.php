<?php
$base = '../';
require_once '../includes/init.php';
requireAdmin();
$pageTitle = 'Order Detail';

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = $orderModel->getById($orderId);

if (!$order) {
    header('Location: orders.php');
    exit;
}

// Admin updates the order status -> this also writes a new tracking history row,
// which is exactly what the customer sees on order-tracking.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $newStatus = clean($_POST['order_status']);
    $note = clean($_POST['note']);
    $allowed = ['placed', 'processing', 'shipped', 'delivered', 'cancelled'];

    if (in_array($newStatus, $allowed)) {
        $orderModel->updateStatus($order['id'], $newStatus);
        $orderModel->addTracking($order['id'], $newStatus, $note ?: "Order status changed to $newStatus.");

        // Notify the customer by email (Topic 24)
        $customer = $userModel->findById($order['user_id']);
        sendEmailNotification(
            $customer['email'],
            'Your Order Status Has Changed',
            "Your order {$order['tracking_code']} is now: <strong>$newStatus</strong><br>$note"
        );

        setFlash('success', 'Order status updated.');
        header('Location: order-detail.php?id=' . $order['id']);
        exit;
    }
}

$items = $orderModel->getItems($order['id']);
$history = $orderModel->getTrackingHistory($order['id']);
$customer = $userModel->findById($order['user_id']);

include '../includes/header.php';
?>

<a href="orders.php" class="back-link mt-20"><i class='bx bx-arrow-back'></i> Back to Orders</a>
<h1 class="page-title">Order #<?php echo $order['id']; ?> &mdash; <?php echo htmlspecialchars($order['tracking_code']); ?></h1>

<div class="card mt-20">
    <p><i class='bx bx-user'></i> <strong>Customer:</strong> <?php echo htmlspecialchars($customer['full_name']); ?> (<?php echo htmlspecialchars($customer['email']); ?>)</p>
    <p class="mt-20"><i class='bx bx-map'></i> <strong>Shipping:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?>, <?php echo htmlspecialchars($order['shipping_city']); ?> - <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
    <p class="mt-20"><i class='bx bx-credit-card'></i> <strong>Payment Method:</strong> <?php echo strtoupper($order['payment_method']); ?></p>
</div>

<h3 class="section-title">Items</h3>
<div class="table-wrap">
    <table>
        <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td><?php echo money($item['price']); ?></td>
                <td><?php echo (int)$item['quantity']; ?></td>
                <td><?php echo money($item['price'] * $item['quantity']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<p class="mt-20"><strong>Total: <?php echo money($order['total_amount']); ?></strong></p>

<h3 class="section-title">Update Order Status</h3>
<form method="POST" class="card" style="max-width:520px;">
    <div class="form-group">
        <label>Status</label>
        <select name="order_status">
            <?php foreach (['placed','processing','shipped','delivered','cancelled'] as $status): ?>
                <option value="<?php echo $status; ?>" <?php echo $order['order_status'] === $status ? 'selected' : ''; ?>>
                    <?php echo ucfirst($status); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Note (optional, shown to the customer)</label>
        <input type="text" name="note" placeholder="e.g. Package handed to courier">
    </div>
    <button type="submit" name="update_status" class="btn btn-full">Update Status</button>
</form>

<h3 class="section-title">Tracking History</h3>
<?php foreach ($history as $entry): ?>
    <div class="card mb-20" style="padding:16px;">
        <span class="status-badge status-<?php echo $entry['status']; ?>"><?php echo ucfirst($entry['status']); ?></span>
        <span class="small-text"> &mdash; <?php echo htmlspecialchars($entry['note']); ?> (<?php echo formatDate($entry['updated_at']); ?>)</span>
    </div>
<?php endforeach; ?>

<?php include '../includes/footer.php'; ?>
