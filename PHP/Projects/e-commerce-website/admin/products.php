<?php
$base = '../';
require_once '../includes/init.php';
requireAdmin();
$pageTitle = 'Manage Products';

if (isset($_GET['delete'])) {
    $db->run("DELETE FROM products WHERE id = ?", "i", [(int)$_GET['delete']]);
    setFlash('success', 'Product deleted.');
    header('Location: products.php');
    exit;
}

$products = $productModel->getAll(200);

include '../includes/header.php';
?>

<div class="flex flex-wrap gap-10" style="justify-content:space-between; align-items:center;">
    <h1 class="page-title" style="margin:0;">Manage Products</h1>
    <a href="product-form.php" class="btn"><i class='bx bx-plus'></i> Add New Product</a>
</div>

<div class="table-wrap mt-20">
    <table>
        <tr><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Featured</th><th>Actions</th></tr>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><img src="../assets/images/products/<?php echo htmlspecialchars($p['image']); ?>" onerror="this.src='../assets/images/no-image.png'" style="width:44px;height:44px;object-fit:contain;border-radius:8px;background:#f7f6ff;padding:4px;"></td>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo money($p['price']); ?></td>
                <td><?php echo (int)$p['stock']; ?></td>
                <td><?php echo $p['is_featured'] ? "<span class='status-badge status-delivered'>Yes</span>" : "<span class='status-badge status-cancelled'>No</span>"; ?></td>
                <td>
                    <a href="product-form.php?id=<?php echo $p['id']; ?>" class="btn btn-small"><i class='bx bx-edit'></i></a>
                    <a href="products.php?delete=<?php echo $p['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete this product?');"><i class='bx bx-trash'></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
