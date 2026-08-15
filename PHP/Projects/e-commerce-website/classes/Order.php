<?php
/*
    classes/Order.php
    ---------------------------------------------
    Topic 17: OOP - handles placing orders, saving order
    items, and building the order tracking history.
*/

class Order {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Creates the order row itself, returns the new order id
    public function create($userId, $total, $paymentMethod, $address, $city, $phone) {
        $trackingCode = generateTrackingCode();

        $orderId = $this->db->run(
            "INSERT INTO orders (user_id, total_amount, payment_method, shipping_address, shipping_city, shipping_phone, tracking_code)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            "idsssss", // i=user_id, d=total, s=payment_method, s=address, s=city, s=phone, s=tracking_code
            [$userId, $total, $paymentMethod, $address, $city, $phone, $trackingCode]
        );

        return ['id' => $orderId, 'tracking_code' => $trackingCode];
    }

    public function addItem($orderId, $productId, $productName, $price, $quantity) {
        $this->db->run(
            "INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)",
            "iisdi",
            [$orderId, $productId, $productName, $price, $quantity]
        );
    }

    public function addTracking($orderId, $status, $note = '') {
        $this->db->run(
            "INSERT INTO order_tracking (order_id, status, note) VALUES (?, ?, ?)",
            "iss",
            [$orderId, $status, $note]
        );
    }

    public function getUserOrders($userId) {
        return $this->db->select(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC",
            "i",
            [$userId]
        );
    }

    public function getById($orderId) {
        return $this->db->selectOne("SELECT * FROM orders WHERE id = ?", "i", [$orderId]);
    }

    public function getByTrackingCode($code) {
        return $this->db->selectOne("SELECT * FROM orders WHERE tracking_code = ?", "s", [$code]);
    }

    public function getItems($orderId) {
        return $this->db->select("SELECT * FROM order_items WHERE order_id = ?", "i", [$orderId]);
    }

    public function getTrackingHistory($orderId) {
        return $this->db->select(
            "SELECT * FROM order_tracking WHERE order_id = ? ORDER BY updated_at ASC",
            "i",
            [$orderId]
        );
    }

    public function updateStatus($orderId, $status) {
        return $this->db->run("UPDATE orders SET order_status = ? WHERE id = ?", "si", [$status, $orderId]);
    }
}
?>
