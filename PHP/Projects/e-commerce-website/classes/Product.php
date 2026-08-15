<?php
/*
    classes/Product.php
    ---------------------------------------------
    Topic 17: OOP - a simple Product class that knows
    how to fetch product data from the database.
*/

class Product {
    private $db;

    public function __construct($db) {
        $this->db = $db; // $db is an instance of our Database class
    }

    public function getAll($limit = 100) {
        return $this->db->select(
            "SELECT * FROM products ORDER BY created_at DESC LIMIT ?",
            "i",
            [$limit]
        );
    }

    public function getFeatured($limit = 6) {
        return $this->db->select(
            "SELECT * FROM products WHERE is_featured = 1 LIMIT ?",
            "i",
            [$limit]
        );
    }

    public function getBySlug($slug) {
        return $this->db->selectOne(
            "SELECT * FROM products WHERE slug = ?",
            "s",
            [$slug]
        );
    }

    public function getById($id) {
        return $this->db->selectOne(
            "SELECT * FROM products WHERE id = ?",
            "i",
            [$id]
        );
    }

    public function getByCategory($categoryId) {
        return $this->db->select(
            "SELECT * FROM products WHERE category_id = ?",
            "i",
            [$categoryId]
        );
    }

    public function search($keyword) {
        $likeKeyword = "%" . $keyword . "%";
        return $this->db->select(
            "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?",
            "ss",
            [$likeKeyword, $likeKeyword]
        );
    }

    // Works out the "you save X%" badge for products with a discount
    public function discountPercent($product) {
        if (empty($product['discount_price'])) {
            return 0;
        }
        $price = (float)$product['price'];
        $discount = (float)$product['discount_price'];
        if ($price <= 0) return 0;
        return round((($price - $discount) / $price) * 100);
    }

    // The price the customer actually pays
    public function finalPrice($product) {
        return !empty($product['discount_price']) ? $product['discount_price'] : $product['price'];
    }
}
?>
