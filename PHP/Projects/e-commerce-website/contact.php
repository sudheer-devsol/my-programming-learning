<?php
$base = '';
require_once 'includes/init.php';
$pageTitle = 'Contact Us';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $message = clean($_POST['message']);

    if (empty($name)) $errors[] = "Name is required.";
    if (!isValidEmail($email)) $errors[] = "Please enter a valid email.";
    if (strlen($message) < 10) $errors[] = "Message must be at least 10 characters.";

    if (count($errors) === 0) {
        $db->run(
            "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)",
            "sss",
            [$name, $email, $message]
        );

        // Topic 24: Sending Email
        require_once __DIR__ . '/config/mail.php';
        sendEmailNotification(
            MAIL_ADMIN_EMAIL,
            'New Contact Message from ' . $name,
            nl2br(htmlspecialchars($message)) . "<br><br>Reply to: $email"
        );

        setFlash('success', 'Thanks for reaching out! We will get back to you soon.');
        header('Location: contact.php');
        exit;
    }
}

include 'includes/header.php';
?>

<h1 class="page-title text-center">Get in Touch</h1>
<p class="section-sub text-center" style="margin-left:0;">Questions, feedback, or just want to say hi? We'd love to hear from you.</p>

<div class="category-strip mt-20 mb-20">
    <div class="category-card">
        <span class="cat-icon"><i class='bx bx-map'></i></span>
        <h3>123 Market Street, Karachi</h3>
    </div>
    <div class="category-card">
        <span class="cat-icon"><i class='bx bx-envelope'></i></span>
        <h3>support@simpleshop.com</h3>
    </div>
    <div class="category-card">
        <span class="cat-icon"><i class='bx bx-phone'></i></span>
        <h3>+92 300 1234567</h3>
    </div>
</div>

<div class="form-box">
    <h2 class="text-center">Send us a Message</h2>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-error"><i class='bx bx-error-circle'></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>
    <p id="js-error" class="form-error"></p>

    <form method="POST" id="contactForm">
        <div class="form-group">
            <label>Your Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Your Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea name="message" id="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-full">Send Message <i class='bx bx-send'></i></button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
