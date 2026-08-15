<?php
$base = ''; // we are in the root folder
require_once 'includes/init.php';

$pageTitle = 'Home';

// Get slider images and featured products from the database
$sliders = $db->select("SELECT * FROM sliders ORDER BY sort_order ASC");
$featuredProducts = $productModel->getFeatured(6);
$categories = $db->select("SELECT * FROM categories");

// A small icon set to visually distinguish categories (falls back to a generic tag icon)
$categoryIcons = [
    'electronics'  => 'bx bx-mobile-alt',
    'fashion'      => 'bx bx-t-shirt',
    'home-living'  => 'bx bx-home-heart',
    'sports'       => 'bx bx-football',
];

include 'includes/header.php';
?>

<!-- ================= IMAGE SLIDER (Topic 12: JavaScript) ================= -->
<div class="slider">
    <?php foreach ($sliders as $index => $slide): ?>
        <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>"
             style="background: linear-gradient(135deg, #<?php echo substr(md5($slide['title']),0,6); ?>, #1e1b2e);">
            <div class="slide-overlay">
                <span class="eyebrow">SimpleShop</span>
                <h2><?php echo htmlspecialchars($slide['title']); ?></h2>
                <p><?php echo htmlspecialchars($slide['subtitle']); ?></p>
                <a href="<?php echo htmlspecialchars($slide['link_url']); ?>" class="btn">
                    Shop Now <i class='bx bx-right-arrow-alt'></i>
                </a>
            </div>
        </div>
    <?php endforeach; ?>

    <button class="slider-arrow prev"><i class='bx bx-chevron-left'></i></button>
    <button class="slider-arrow next"><i class='bx bx-chevron-right'></i></button>

    <div class="slider-dots">
        <?php foreach ($sliders as $index => $slide): ?>
            <span class="<?php echo $index === 0 ? 'active' : ''; ?>"></span>
        <?php endforeach; ?>
    </div>
</div>

<!-- ================= CATEGORIES ================= -->
<h2 class="section-title reveal">Shop by Category</h2>
<p class="section-sub reveal">Browse our most popular departments</p>
<div class="category-strip">
    <?php foreach ($categories as $cat): ?>
        <a href="products.php?category=<?php echo $cat['id']; ?>" class="category-card reveal">
            <span class="cat-icon"><i class='<?php echo $categoryIcons[$cat['slug']] ?? 'bx bx-purchase-tag'; ?>'></i></span>
            <h3><?php echo htmlspecialchars($cat['name']); ?></h3>
        </a>
    <?php endforeach; ?>
</div>

<!-- ================= FEATURED PRODUCTS ================= -->
<h2 class="section-title reveal">Featured Products</h2>
<p class="section-sub reveal">Hand-picked items our customers love</p>
<div class="product-grid">
    <?php foreach ($featuredProducts as $product): ?>
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

<!-- ================= WHY SHOP WITH US ================= -->
<h2 class="section-title reveal">Why Shop With Us</h2>
<p class="section-sub reveal">A few reasons customers keep coming back</p>
<div class="category-strip mb-20">
    <div class="category-card reveal">
        <span class="cat-icon"><i class='bx bx-package'></i></span>
        <h3>Fast Delivery</h3>
    </div>
    <div class="category-card reveal">
        <span class="cat-icon"><i class='bx bx-shield-quarter'></i></span>
        <h3>Secure Checkout</h3>
    </div>
    <div class="category-card reveal">
        <span class="cat-icon"><i class='bx bx-support'></i></span>
        <h3>Live Support</h3>
    </div>
    <div class="category-card reveal">
        <span class="cat-icon"><i class='bx bx-undo'></i></span>
        <h3>Easy Tracking</h3>
    </div>
</div>

<script src="assets/js/slider.js"></script>

<?php include 'includes/footer.php'; ?>
