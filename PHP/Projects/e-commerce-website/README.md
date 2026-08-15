# SimpleShop — Beginner-Friendly PHP E-Commerce Website

SimpleShop is a complete, beginner-friendly full-stack e-commerce website built with **Core PHP, MySQL/MariaDB, HTML, CSS, and Vanilla JavaScript**.

The project was designed to keep the code simple and understandable while demonstrating the practical concepts involved in building a database-driven e-commerce application. It includes customer authentication, product management, shopping cart, checkout, order tracking, reviews, customer chat, email notifications, and an admin panel.

The application was tested with **PHP 8.3 and MariaDB** across the main workflows, including registration, cart, checkout, order tracking, customer chat, email notifications, and administration.

## Features

### Customer Features

* Home page with an auto-playing image slider
* Product catalog
* Product search
* Category filtering
* Product detail pages
* Customer product reviews
* Session-based shopping cart
* Remember Me login using cookies
* Customer registration and login
* Checkout system
* Order history
* Order tracking with visual progress
* Customer-to-admin chat
* Contact form
* Email notifications

### Checkout

The checkout system provides three payment options:

* Cash on Delivery
* Card — demo only
* PayPal — demo only

No real payment gateway is connected. The payment options are included for learning and demonstration purposes.

### Order Tracking

Customers can track their orders through a visual progress system:

**Placed → Processing → Shipped → Delivered**

When the administrator updates an order status, the customer's tracking page reflects the updated status automatically.

### Customer Chat

The project includes an AJAX-powered chat system between logged-in customers and the administrator.

The chat periodically checks for new messages, allowing customers and the admin to communicate without manually refreshing the page.

### Admin Panel

The Admin Panel provides:

* Dashboard statistics
* Product management
* Add, edit, and delete products
* Product image uploads
* Order management
* Order status updates
* Customer chat management
* Customer communication
* Order-related email notifications

## UI Design & User Experience

The original SimpleShop application already had a functional PHP and MySQL backend with dynamic data, authentication, sessions, shopping cart, checkout, chat, reviews, email functionality, and an admin panel.

The project was later given a complete frontend redesign to make the application more modern, attractive, responsive, and user-friendly while keeping the existing backend architecture and functionality intact.

The redesign focused mainly on the **interface and user experience rather than rebuilding the backend**.

### Modern Design System

The frontend now includes:

* Purple and teal gradient theme
* Poppins and Inter typography
* Boxicons icons
* Modern cards and buttons
* Hover and lift animations
* Color-coded status badges
* Glass-style sticky header
* Animated hero slider
* Responsive mobile navigation
* Scroll-reveal animations
* Modern empty states
* Improved chat interface
* Modern admin dashboard statistic cards

### Header & Footer

The shared header and footer were redesigned to provide a consistent experience throughout the application.

The updated layout includes:

* Sticky navigation
* Icon-based navigation
* Mobile hamburger menu
* Utility bar
* Multi-column footer
* Social media links
* Back-to-top button

Because these components are shared across the website, the updated design is applied consistently throughout the different pages.

### JavaScript Improvements

The existing JavaScript functionality was preserved while additional interface interactions were introduced.

These include:

* Mobile menu toggle
* Back-to-top button
* Scroll-reveal animations
* Auto-dismissing flash messages
* Improved loading states
* Improved Add to Cart feedback

The existing validation and AJAX functionality remain part of the application.

### Redesigned Pages

The new interface was applied across both customer and admin areas, including:

* Home page
* Product listing
* Product details
* Shopping cart
* Checkout
* Login
* Registration
* Customer account
* Order history
* Order tracking
* Customer chat
* Contact page
* Admin dashboard
* Product management
* Order management
* Admin chat

The redesigned pages use cleaner layouts, improved spacing, modern cards, icons, status indicators, star ratings, responsive grids, and clearer empty states.

## User Roles

SimpleShop uses two roles:

* **Admin**
* **Customer**

The project does not include a seller or multi-vendor system.

## Backend Compatibility

The redesign was made without changing the application's core backend architecture.

The existing:

* PHP logic
* MySQL database
* Sessions
* Authentication
* Shopping cart
* Checkout process
* AJAX functionality
* Forms
* Admin operations
* Email functionality

continue to work with the redesigned interface.

The goal was to improve the presentation and usability of the application without unnecessarily rebuilding its existing functionality.

## What This Project Demonstrates

SimpleShop brings together many fundamental PHP and web development concepts, including:

* PHP basics
* Variables and conditions
* Loops and branching
* HTML forms
* CSS
* Sessions
* Cookies
* Arrays
* String functions
* Date and time handling
* PHP functions
* JavaScript
* Form validation
* MySQL and PHP integration
* Object-oriented PHP
* AJAX
* Error handling
* Email integration with PHPMailer
* Full-stack application development

The project combines frontend development, PHP backend programming, MySQL database operations, authentication, sessions, cookies, CRUD operations, AJAX, OOP, file uploads, order processing, error handling, email integration, and an administrator dashboard into one complete application.

## Requirements

To run SimpleShop locally, you need:

* XAMPP, WAMP, MAMP, or another PHP 8+ environment
* MySQL or MariaDB
* A web browser
* A code editor such as VS Code

Dreamweaver can also be used as an editor, but it is not required.

## Installation

### 1. Copy the Project

Copy the complete `ecommerce` folder into your server's web directory.

For XAMPP on Windows:

```text
C:\xampp\htdocs\ecommerce
```

For XAMPP on macOS:

```text
/Applications/XAMPP/htdocs/ecommerce
```

### 2. Start the Server

Open the XAMPP Control Panel and start:

* Apache
* MySQL

### 3. Import the Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Select **Import** and choose:

```text
database.sql
```

The SQL file creates the `simpleshop` database, required tables, and sample product data.

### 4. Configure the Database

Open:

```text
config/db.php
```

Check the database configuration:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'simpleshop');
```

### 5. Run the Website

Open:

```text
http://localhost/ecommerce/index.php
```

The SimpleShop home page should now be available.

## Login

### Customer

Customers can create an account through the **Register** page.

### Admin

A default admin account is included in the database:

```text
Email: admin@simpleshop.com
Password: admin123
```

The Admin Panel can be accessed from:

```text
http://localhost/ecommerce/admin/dashboard.php
```

## Product Images

Sample products use a placeholder image:

```text
assets/images/no-image.png
```

Real product images can be uploaded through:

**Admin → Manage Products → Edit**

Uploaded product images are stored in:

```text
assets/images/products/
```

## Email Notifications

SimpleShop uses **PHPMailer with Gmail SMTP** for sending:

* Order confirmations
* Order status updates
* Contact form notifications

To enable email functionality, configure the required SMTP credentials in:

```text
config/mail.php
```

Example:

```php
define('MAIL_USERNAME', 'youraddress@gmail.com');
define('MAIL_PASSWORD', 'your16charapppassword');
define('MAIL_FROM_EMAIL', 'youraddress@gmail.com');
define('MAIL_ADMIN_EMAIL', 'youraddress@gmail.com');
```

The password should be a **Google App Password**, not your normal Gmail password.

For security, never commit real email credentials to a public GitHub repository. Use environment variables or keep sensitive configuration files outside the public repository and add them to `.gitignore`.

The PHPMailer library is included in:

```text
libs/PHPMailer/
```

Composer is not required for this project.

## Project Structure

```text
ecommerce/
├── admin/
│   └── Admin dashboard, products, orders, and chat
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── classes/
│   ├── Database.php
│   ├── Product.php
│   ├── User.php
│   └── Order.php
│
├── config/
│   ├── db.php
│   └── mail.php
│
├── includes/
│   ├── Shared header/footer
│   ├── Functions
│   └── Initialization
│
├── libs/
│   └── PHPMailer/
│
├── database.sql
├── index.php
├── products.php
├── product-detail.php
├── cart.php
├── add-to-cart.php
├── remove-from-cart.php
├── checkout.php
├── process-order.php
├── login.php
├── register.php
├── logout.php
├── account.php
├── order-history.php
├── order-tracking.php
├── chat.php
├── chat-handler.php
└── contact.php
```

## Code Style

SimpleShop intentionally follows a straightforward coding style.

Most of the application uses **procedural PHP** so that beginners can easily understand how the code works.

A few lightweight OOP classes are included for:

* `Database`
* `Product`
* `User`
* `Order`

These classes are kept simple and readable rather than hiding the application behind complex architecture.

The project does not use:

* PHP frameworks
* Composer
* Complex autoloading
* ORM systems
* Heavy abstractions

The purpose is to understand the fundamentals first: PHP, SQL, sessions, cookies, forms, AJAX, authentication, CRUD operations, OOP, file uploads, and email integration.

## Learning Purpose

SimpleShop was developed as a practical full-stack PHP project to demonstrate how different web development concepts can be combined into a working e-commerce application.

It provides hands-on experience with both the backend and frontend sides of a real application while keeping the code accessible for beginners.

The project is intended as a learning and portfolio project and can also serve as a foundation for future improvements such as real payment gateway integration, additional security measures, and more advanced e-commerce functionality.

## Final Note

SimpleShop combines a functional **PHP + MySQL e-commerce backend** with a modern, responsive, and user-friendly frontend.

The UI redesign improves the overall appearance and usability while preserving the application's existing functionality and backend architecture.

Happy coding! 🚀
