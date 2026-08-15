<?php


define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);                 // 587 = TLS (recommended), 465 = SSL
define('MAIL_ENCRYPTION', 'tls');          // 'tls' or 'ssl'

define('MAIL_USERNAME', 'youraddress@gmail.com');   // <-- your Gmail address
define('MAIL_PASSWORD', 'your16charapppassword');   // <-- your Gmail App Password (no spaces)

define('MAIL_FROM_EMAIL', 'youraddress@gmail.com'); // usually same as MAIL_USERNAME
define('MAIL_FROM_NAME', 'SimpleShop');

// Where admin notifications (like new contact messages) get sent
define('MAIL_ADMIN_EMAIL', 'youraddress@gmail.com');
?>
