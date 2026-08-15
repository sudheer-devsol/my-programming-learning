<?php
$base = '../';
require_once '../includes/init.php';
requireAdmin();
$pageTitle = 'Manage Orders';

$orders = $db->select(
    "SELECT orders.*, users.full_name, users.email FROM orders
     JOIN users ON orders.user_id = users.id
     ORDER BY orders.created_at DESC"
);

include '../includes/header.php';
?>

<h1 class="page-title">Manage Orders</h1>

<div class="table-wrap mt-20">
    <table>
        <tr>
            <th>Tracking Code</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Status</th>
            <th></th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($order['tracking_code']); ?></strong></td>
                <td><?php echo htmlspecialchars($order['full_name']); ?><br><span class="small-text"><?php echo htmlspecialchars($order['email']); ?></span></td>
                <td><?php echo formatDate($order['created_at']); ?></td>
                <td><?php echo money($order['total_amount']); ?></td>
                <td><?php echo strtoupper($order['payment_method']); ?></td>
                <td><span class="status-badge status-<?php echo $order['order_status']; ?>"><?php echo ucfirst($order['order_status']); ?></span></td>
                <td><a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-small">View</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
