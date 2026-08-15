<?php
$base = '../';
require_once '../includes/init.php';
requireAdmin();

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = $productId ? $productModel->getById($productId) : null;
$pageTitle = $product ? 'Edit Product' : 'Add Product';
$categories = $db->select("SELECT * FROM categories");
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name']);
    $categoryId = (int)$_POST['category_id'];
    $description = clean($_POST['description']);
    $price = (float)$_POST['price'];
    $discountPrice = $_POST['discount_price'] !== '' ? (float)$_POST['discount_price'] : null;
    $stock = (int)$_POST['stock'];
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $slug = makeSlug($name);

    // Handle image upload (kept simple: just move the file, basic checks only)
    $imageName = $product['image'] ?? 'no-image.png';
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $imageName = $slug . '-' . time() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/products/' . $imageName);
        } else {
            $errors[] = "Invalid image format. Allowed: jpg, jpeg, png, gif, webp.";
        }
    }

    if (empty($name) || $price <= 0) {
        $errors[] = "Please enter a valid name and price.";
    }

    if (count($errors) === 0) {
        if ($product) {
            $db->run(
                "UPDATE products SET category_id=?, name=?, slug=?, description=?, price=?, discount_price=?, stock=?, image=?, is_featured=? WHERE id=?",
                "isssddisii",
                [$categoryId, $name, $slug, $description, $price, $discountPrice, $stock, $imageName, $isFeatured, $product['id']]
            );
            setFlash('success', 'Product updated.');
        } else {
            $db->run(
                "INSERT INTO products (category_id, name, slug, description, price, discount_price, stock, image, is_featured) VALUES (?,?,?,?,?,?,?,?,?)",
                "isssddiis",
                [$categoryId, $name, $slug, $description, $price, $discountPrice, $stock, $isFeatured, $imageName]
            );
            setFlash('success', 'Product added.');
        }
        header('Location: products.php');
        exit;
    }
}

include '../includes/header.php';
?>

<a href="products.php" class="back-link mt-20"><i class='bx bx-arrow-back'></i> Back to Products</a>
<h1 class="page-title"><?php echo $pageTitle; ?></h1>

<?php foreach ($errors as $error): ?>
    <div class="alert alert-error"><i class='bx bx-error-circle'></i> <?php echo htmlspecialchars($error); ?></div>
<?php endforeach; ?>

<form method="POST" enctype="multipart/form-data" class="card" style="max-width:640px;">
    <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>">
    </div>
    <div class="form-group">
        <label>Category</label>
        <select name="category_id">
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
    </div>
    <div class="input-row">
        <div class="form-group">
            <label>Price ($)</label>
            <input type="number" step="0.01" name="price" required value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Discount Price ($) - optional</label>
            <input type="number" step="0.01" name="discount_price" value="<?php echo htmlspecialchars($product['discount_price'] ?? ''); ?>">
        </div>
    </div>
    <div class="form-group">
        <label>Stock Quantity</label>
        <input type="number" name="stock" required value="<?php echo htmlspecialchars($product['stock'] ?? '0'); ?>">
    </div>
    <div class="form-group">
        <label>Product Image</label>
        <input type="file" name="image">
    </div>
    <div class="form-group checkbox-line">
        <input type="checkbox" name="is_featured" id="is_featured" <?php echo (!empty($product['is_featured'])) ? 'checked' : ''; ?>>
        <label for="is_featured" style="margin:0;">Show on homepage (Featured)</label>
    </div>
    <button type="submit" class="btn btn-full">Save Product</button>
</form>

<?php include '../includes/footer.php'; ?>
