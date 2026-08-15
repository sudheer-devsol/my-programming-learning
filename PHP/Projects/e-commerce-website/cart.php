<?php
$base = '';
require_once 'includes/init.php';
$pageTitle = 'Your Cart';

// Update quantities if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    $cart = getCart();
    foreach ($_POST['quantity'] as $productId => $qty) {
        $qty = (int)$qty;
        if ($qty > 0 && isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $qty;
        }
    }
    $_SESSION['cart'] = $cart;
    setFlash('success', 'Cart updated.');
    header('Location: cart.php');
    exit;
}

$cart = getCart();

include 'includes/header.php';
?>

<h1 class="page-title">Your Shopping Cart</h1>

<?php if (count($cart) === 0): ?>
    <div class="empty-state">
        <i class='bx bx-cart'></i>
        <h3>Your cart is empty</h3>
        <p>Looks like you haven't added anything yet.</p>
        <a href="products.php" class="btn">Continue Shopping</a>
    </div>
<?php else: ?>
    <div class="grid-2 mt-20">
        <div>
            <form method="POST">
                <?php foreach ($cart as $item): ?>
                    <div class="cart-row">
                        <img src="assets/images/products/<?php echo htmlspecialchars($item['image']); ?>"
                             onerror="this.src='assets/images/no-image.png'" alt="">
                        <div class="grow">
                            <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                            <span class="small-text"><?php echo money($item['price']); ?> each</span>
                        </div>
                        <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo (int)$item['quantity']; ?>" min="1">
                        <div style="width:100px; text-align:right; font-weight:600;"><?php echo money($item['price'] * $item['quantity']); ?></div>
                        <a href="remove-from-cart.php?id=<?php echo $item['id']; ?>" class="btn btn-small btn-secondary"><i class='bx bx-trash'></i></a>
                    </div>
                <?php endforeach; ?>

                <button type="submit" name="update_cart" class="btn btn-secondary btn-small mt-20"><i class='bx bx-refresh'></i> Update Cart</button>
            </form>
        </div>

        <div>
            <div class="cart-total-box" style="margin-left:0;">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span> <strong><?php echo money(cartTotal()); ?></strong>
                </div>
                <div class="summary-row small-text">
                    <span>Shipping</span> <span><?php echo cartTotal() >= 50 ? 'Free' : 'Calculated at checkout'; ?></span>
                </div>
                <hr class="mt-20">
                <div class="summary-total">
                    <strong>Total</strong> <strong><?php echo money(cartTotal()); ?></strong>
                </div>
                <a href="checkout.php" class="btn btn-full">Proceed to Checkout <i class='bx bx-right-arrow-alt'></i></a>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
