</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a href="<?php echo isset($base) ? $base : ''; ?>index.php" class="logo"><i class='bx bxs-store-alt'></i> SimpleShop</a>
                <p>Your friendly neighborhood online store — quality products, honest prices, and real people behind the support chat.</p>
                <div class="footer-social">
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-instagram'></i></a>
                    <a href="#"><i class='bx bxl-twitter'></i></a>
                    <a href="#"><i class='bx bxl-youtube'></i></a>
                </div>
            </div>
            <div class="footer-links-col">
                <h4>Quick Links</h4>
                <a href="<?php echo isset($base) ? $base : ''; ?>index.php">Home</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>products.php">All Products</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>cart.php">My Cart</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>contact.php">Contact Us</a>
            </div>
            <div class="footer-links-col">
                <h4>My Account</h4>
                <a href="<?php echo isset($base) ? $base : ''; ?>account.php">Profile</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>order-history.php">My Orders</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>order-tracking.php">Track Order</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>chat.php">Chat Support</a>
            </div>
            <div>
                <h4>Get in Touch</h4>
                <div class="footer-contact-item"><i class='bx bx-map'></i> 123 Market Street, Karachi, Pakistan</div>
                <div class="footer-contact-item"><i class='bx bx-envelope'></i> support@simpleshop.com</div>
                <div class="footer-contact-item"><i class='bx bx-phone'></i> +92 300 1234567</div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?php echo date('Y'); ?> <a href="<?php echo isset($base) ? $base : ''; ?>index.php">SimpleShop</a> &mdash; All rights reserved.
        </div>
    </div>
</footer>

<button class="back-to-top" id="backToTop" aria-label="Back to top"><i class='bx bx-up-arrow-alt'></i></button>

<script src="<?php echo isset($base) ? $base : ''; ?>assets/js/script.js"></script>
</body>
</html>
