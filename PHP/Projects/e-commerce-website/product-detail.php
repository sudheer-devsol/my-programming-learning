<?php
$base = '';
require_once 'includes/init.php';

$slug = isset($_GET['slug']) ? clean($_GET['slug']) : '';
$product = $productModel->getBySlug($slug);

if (!$product) {
    header('Location: products.php');
    exit;
}

$pageTitle = $product['name'];

// Handle a new review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isLoggedIn()) {
        setFlash('error', 'Please login to leave a review.');
    } else {
        $rating = (int)$_POST['rating'];
        $comment = clean($_POST['comment']);
        $db->run(
            "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)",
            "iiis",
            [$product['id'], $_SESSION['user_id'], $rating, $comment]
        );
        setFlash('success', 'Thanks for your review!');
    }
    header('Location: product-detail.php?slug=' . $slug);
    exit;
}

$reviews = $db->select(
    "SELECT reviews.*, users.full_name FROM reviews
     JOIN users ON reviews.user_id = users.id
     WHERE product_id = ? ORDER BY reviews.created_at DESC",
    "i",
    [$product['id']]
);

$avgRating = 0;
if (count($reviews) > 0) {
    $sum = 0;
    foreach ($reviews as $r) { $sum += (int)$r['rating']; }
    $avgRating = round($sum / count($reviews), 1);
}

include 'includes/header.php';
?>

<a href="products.php" class="back-link mt-20"><i class='bx bx-arrow-back'></i> Back to Products</a>

<div class="grid-2">
    <div class="card" style="display:flex; align-items:center; justify-content:center;">
        <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>"
             onerror="this.src='assets/images/no-image.png'"
             style="width:100%; max-height:420px; object-fit:contain;" alt="">
    </div>
    <div>
        <?php if (!empty($product['is_featured'])): ?><span class="badge" style="position:static; display:inline-flex;">Featured</span><?php endif; ?>
        <h1 class="mt-20"><?php echo htmlspecialchars($product['name']); ?></h1>

        <p class="mt-20">
            <span class="stars"><?php echo str_repeat('★', round($avgRating)) . str_repeat('☆', 5 - round($avgRating)); ?></span>
            <span class="small-text"> <?php echo $avgRating; ?> (<?php echo count($reviews); ?> review<?php echo count($reviews) === 1 ? '' : 's'; ?>)</span>
        </p>

        <p class="small-text mt-20"><i class='bx bx-package'></i> Stock available: <?php echo (int)$product['stock']; ?></p>

        <p style="font-size:26px; margin:16px 0;">
            <?php if (!empty($product['discount_price'])): ?>
                <span class="price-old"><?php echo money($product['price']); ?></span>
            <?php endif; ?>
            <span class="price-new"><?php echo money($productModel->finalPrice($product)); ?></span>
        </p>

        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

        <form method="POST" action="add-to-cart.php" class="mt-20 flex gap-10" style="align-items:flex-end;">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <div class="form-group" style="max-width:100px; margin-bottom:0;">
                <label>Quantity</label>
                <input type="number" name="quantity" value="1" min="1" max="<?php echo (int)$product['stock']; ?>">
            </div>
            <button class="btn" type="submit"><i class='bx bx-cart-add'></i> Add to Cart</button>
        </form>
    </div>
</div>

<!-- ================= REVIEWS ================= -->
<h2 class="section-title reveal">Customer Reviews (<?php echo count($reviews); ?>)</h2>

<?php if (count($reviews) === 0): ?>
    <p class="small-text">No reviews yet — be the first to share your thoughts!</p>
<?php endif; ?>

<?php foreach ($reviews as $review): ?>
    <div class="card mb-20">
        <div class="flex" style="justify-content:space-between; align-items:center;">
            <strong><?php echo htmlspecialchars($review['full_name']); ?></strong>
            <span class="stars"><?php echo str_repeat('★', (int)$review['rating']) . str_repeat('☆', 5 - (int)$review['rating']); ?></span>
        </div>
        <p class="small-text mt-20"><?php echo formatDate($review['created_at']); ?></p>
        <p class="mt-20"><?php echo htmlspecialchars($review['comment']); ?></p>
    </div>
<?php endforeach; ?>

<?php if (isLoggedIn()): ?>
    <form method="POST" class="form-box" style="margin-left:0;">
        <h3>Write a Review</h3>
        <div class="form-group mt-20">
            <label>Rating</label>
            <select name="rating" required>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Good</option>
                <option value="3">3 - Average</option>
                <option value="2">2 - Poor</option>
                <option value="1">1 - Terrible</option>
            </select>
        </div>
        <div class="form-group">
            <label>Comment</label>
            <textarea name="comment" rows="3" required></textarea>
        </div>
        <button type="submit" name="submit_review" class="btn btn-full">Submit Review</button>
    </form>
<?php else: ?>
    <p class="mb-20"><a href="login.php">Login</a> to leave a review.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
