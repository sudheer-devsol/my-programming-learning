<?php
$base = '';
require_once 'includes/init.php';
requireLogin();
$pageTitle = 'Track Order';

$code = isset($_GET['code']) ? clean($_GET['code']) : (isset($_POST['code']) ? clean($_POST['code']) : '');
$order = null;
$items = [];
$history = [];

if (!empty($code)) {
    $order = $orderModel->getByTrackingCode($code);
    if ($order && (int)$order['user_id'] !== (int)$_SESSION['user_id'] && !isAdmin()) {
        $order = null; // don't let people track other users' orders
    }
    if ($order) {
        $items = $orderModel->getItems($order['id']);
        $history = $orderModel->getTrackingHistory($order['id']);
    }
}

// The fixed set of steps we show visually
$allSteps = ['placed' => 'Order Placed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered'];
$stepIcons = ['placed' => 'bx-receipt', 'processing' => 'bx-cog', 'shipped' => 'bx-package', 'delivered' => 'bx-home-heart'];
$stepOrder = array_keys($allSteps);
$currentStepIndex = $order ? array_search($order['order_status'], $stepOrder) : -1;

include 'includes/header.php';
?>

<h1 class="page-title">Track Your Order</h1>

<form method="GET" action="order-tracking.php" style="max-width:460px; display:flex; gap:10px; margin-bottom:20px;">
    <input type="text" name="code" placeholder="Enter tracking code e.g. SS-2026-AB12CD" value="<?php echo htmlspecialchars($code); ?>">
    <button class="btn" type="submit"><i class='bx bx-search'></i></button>
</form>

<?php if (!empty($code) && !$order): ?>
    <div class="alert alert-error"><i class='bx bx-error-circle'></i> No order found with that tracking code.</div>
<?php endif; ?>

<?php if ($order): ?>
    <div class="card mt-20">
        <div class="flex flex-wrap gap-20" style="justify-content:space-between;">
            <p><strong>Tracking Code:</strong> <?php echo htmlspecialchars($order['tracking_code']); ?></p>
            <p><strong>Placed on:</strong> <?php echo formatDate($order['created_at']); ?></p>
        </div>

        <?php if ($order['order_status'] === 'cancelled'): ?>
            <div class="alert alert-error mt-20"><i class='bx bx-x-circle'></i> This order was cancelled.</div>
        <?php else: ?>
            <div class="tracking-steps">
                <?php foreach ($allSteps as $key => $label): ?>
                    <?php $stepIndex = array_search($key, $stepOrder); ?>
                    <div class="tracking-step <?php echo $stepIndex <= $currentStepIndex ? 'done' : ''; ?>">
                        <div class="dot"><i class='bx <?php echo $stepIndex <= $currentStepIndex ? 'bx-check' : $stepIcons[$key]; ?>'></i></div>
                        <span><?php echo $label; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <h3 class="section-title">Order Items</h3>
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

    <h3 class="section-title">Tracking History</h3>
    <?php foreach ($history as $entry): ?>
        <div class="card mb-20" style="padding:16px;">
            <span class="status-badge status-<?php echo $entry['status']; ?>"><?php echo ucfirst($entry['status']); ?></span>
            <span class="small-text"> &mdash; <?php echo htmlspecialchars($entry['note']); ?> (<?php echo formatDate($entry['updated_at']); ?>)</span>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
