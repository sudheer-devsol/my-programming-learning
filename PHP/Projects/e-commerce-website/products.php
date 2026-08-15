<?php
$base = '';
require_once 'includes/init.php';
$pageTitle = 'Products';

// --- Handle filters coming from the URL ($_GET) ---
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$keyword = isset($_GET['q']) ? clean($_GET['q']) : '';

if (!empty($keyword)) {
    $products = $productModel->search($keyword);
} elseif ($categoryId > 0) {
    $products = $productModel->getByCategory($categoryId);
} else {
    $products = $productModel->getAll();
}

$categories = $db->select("SELECT * FROM categories");

include 'includes/header.php';
?>

<h1 class="page-title">Our Products</h1>
<p class="section-sub" style="margin-left:0;">
    <?php echo count($products); ?> item<?php echo count($products) === 1 ? '' : 's'; ?> found
    <?php if (!empty($keyword)): ?> for "<?php echo htmlspecialchars($keyword); ?>"<?php endif; ?>
</p>

<form method="GET" action="products.php" class="mt-20">
    <div style="display:flex; gap:10px; max-width:500px;">
        <input type="text" name="q" placeholder="Search products..." value="<?php echo htmlspecialchars($keyword); ?>">
        <button class="btn" type="submit"><i class='bx bx-search'></i></button>
    </div>
</form>

<div class="flex flex-wrap gap-10 mt-20 mb-20">
    <a href="products.php" class="btn btn-small btn-chip <?php echo $categoryId === 0 ? 'btn-secondary' : ''; ?>">All</a>
    <?php foreach ($categories as $cat): ?>
        <a href="products.php?category=<?php echo $cat['id']; ?>"
           class="btn btn-small btn-chip <?php echo $categoryId === (int)$cat['id'] ? 'btn-secondary' : ''; ?>">
           <?php echo htmlspecialchars($cat['name']); ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (count($products) === 0): ?>
    <div class="empty-state">
        <i class='bx bx-search-alt'></i>
        <h3>No products found</h3>
        <p>Try a different search term or browse all categories.</p>
        <a href="products.php" class="btn">View All Products</a>
    </div>
<?php else: ?>
<div class="product-grid">
    <?php foreach ($products as $product): ?>
        <div class="product-card reveal">
            <div class="card-img">
                <?php if ($productModel->discountPercent($product) > 0): ?>
                    <span class="badge">-<?php echo $productModel->discountPercent($product); ?>%</span>
                <?php endif; ?>
                <a href="product-detail.php?slug=<?php echo $product['slug']; ?>">
                    <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>"
                         onerror="this.src='assets/images/no-image.png'" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </a>
            </div>
            <div class="card-body">
                <a href="product-detail.php?slug=<?php echo $product['slug']; ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                </a>
                <p class="small-text"><?php echo shortText($product['description'], 60); ?></p>
                <p>
                    <?php if (!empty($product['discount_price'])): ?>
                        <span class="price-old"><?php echo money($product['price']); ?></span>
                    <?php endif; ?>
                    <span class="price-new"><?php echo money($productModel->finalPrice($product)); ?></span>
                </p>
                <button class="btn btn-small mt-20" onclick="addToCartAjax(<?php echo $product['id']; ?>, this)">
                    <i class='bx bx-cart-add'></i> Add to Cart
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
