<?php
$base = '';
require_once 'includes/init.php';
requireLogin(); // must be logged in to checkout

$pageTitle = 'Checkout';
$cart = getCart();

if (count($cart) === 0) {
    header('Location: cart.php');
    exit;
}

$currentUser = $userModel->findById($_SESSION['user_id']);

include 'includes/header.php';
?>

<h1 class="page-title">Checkout</h1>

<form method="POST" action="process-order.php">
    <div class="grid-2 mt-20">

        <div>
            <div class="card">
                <h3 class="section-title" style="margin-top:0;">Shipping Details</h3>

                <div class="form-group">
                    <label>Full Address</label>
                    <input type="text" name="address" required value="<?php echo htmlspecialchars($currentUser['address']); ?>">
                </div>
                <div class="input-row">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" required value="<?php echo htmlspecialchars($currentUser['city']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" required value="<?php echo htmlspecialchars($currentUser['phone']); ?>">
                    </div>
                </div>

                <h3 class="section-title">Payment Method</h3>
                <div class="payment-options">
                    <label class="payment-option selected">
                        <i class='bx bx-money'></i>
                        <input type="radio" name="payment_method" value="cod" checked> Cash on Delivery
                    </label>
                    <label class="payment-option">
                        <i class='bx bx-credit-card'></i>
                        <input type="radio" name="payment_method" value="card"> Credit / Debit Card
                    </label>
                    <label class="payment-option">
                        <i class='bx bxl-paypal'></i>
                        <input type="radio" name="payment_method" value="paypal"> PayPal
                    </label>
                </div>

                <!-- Card fields only show up if "Card" is chosen (kept very simple on purpose) -->
                <div id="cardFields" style="display:none;">
                    <div class="form-group">
                        <label>Card Number</label>
                        <input type="text" name="card_number" maxlength="16" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="input-row">
                        <div class="form-group">
                            <label>Expiry (MM/YY)</label>
                            <input type="text" name="card_expiry" placeholder="MM/YY">
                        </div>
                        <div class="form-group">
                            <label>CVV</label>
                            <input type="text" name="card_cvv" maxlength="4" placeholder="123">
                        </div>
                    </div>
                    <p class="small-text"><i class='bx bx-info-circle'></i> This is a demo project - no real payment is processed.</p>
                </div>
            </div>
        </div>

        <div>
            <div class="cart-total-box" style="margin-left:0;">
                <h3>Order Summary</h3>
                <?php foreach ($cart as $item): ?>
                    <div class="summary-row small-text">
                        <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                        <span><?php echo money($item['price'] * $item['quantity']); ?></span>
                    </div>
                <?php endforeach; ?>
                <hr class="mt-20">
                <div class="summary-total">
                    <strong>Total</strong> <strong><?php echo money(cartTotal()); ?></strong>
                </div>
                <button type="submit" class="btn btn-full">Place Order <i class='bx bx-check'></i></button>
            </div>
        </div>

    </div>
</form>

<script>
// Show/hide card fields depending on chosen payment method (Topic 12: JavaScript)
document.querySelectorAll('input[name=payment_method]').forEach(function (radio) {
    radio.addEventListener('change', function () {
        document.getElementById('cardFields').style.display = (this.value === 'card') ? 'block' : 'none';
    });
});
</script>

<?php include 'includes/footer.php'; ?>
