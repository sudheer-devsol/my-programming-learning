/* =========================================================
   script.js
   General site JavaScript - Topic 12 & Topic 13
   (client-side form validation, small UI helpers)
========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    // --- Mobile nav toggle ---
    var navToggle = document.getElementById('navToggle');
    var mainNav = document.getElementById('mainNav');
    if (navToggle && mainNav) {
        navToggle.addEventListener('click', function () {
            mainNav.classList.toggle('open');
            var icon = navToggle.querySelector('i');
            if (mainNav.classList.contains('open')) {
                icon.classList.remove('bx-menu');
                icon.classList.add('bx-x');
            } else {
                icon.classList.remove('bx-x');
                icon.classList.add('bx-menu');
            }
        });
    }

    // --- Back to top button ---
    var backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 400) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // --- Scroll-reveal animation for elements with .reveal class ---
    var revealEls = document.querySelectorAll('.reveal');
    if (revealEls.length && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        revealEls.forEach(function (el) { observer.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('visible'); });
    }

    // --- Auto-dismiss flash alerts after a few seconds ---
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function () { alert.remove(); }, 500);
        }, 4500);
    });

    // --- Highlight the selected payment method on checkout.php ---
    var paymentOptions = document.querySelectorAll('.payment-option');
    paymentOptions.forEach(function (option) {
        option.addEventListener('click', function () {
            paymentOptions.forEach(function (o) { o.classList.remove('selected'); });
            option.classList.add('selected');
            option.querySelector('input[type=radio]').checked = true;
        });
    });

    // --- Simple client-side validation for the register form ---
    var registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            var password = document.getElementById('password').value;
            var confirm = document.getElementById('confirm_password').value;
            var errorBox = document.getElementById('js-error');

            if (password.length < 6) {
                e.preventDefault();
                errorBox.textContent = 'Password must be at least 6 characters long.';
                return;
            }
            if (password !== confirm) {
                e.preventDefault();
                errorBox.textContent = 'Passwords do not match.';
                return;
            }
            errorBox.textContent = '';
        });
    }

    // --- Contact form quick validation ---
    var contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            var message = document.getElementById('message').value.trim();
            var errorBox = document.getElementById('js-error');
            if (message.length < 10) {
                e.preventDefault();
                errorBox.textContent = 'Please write at least 10 characters in your message.';
            }
        });
    }
});

// --- AJAX: Add to cart without reloading the page (Topic 19: Ajax) ---
function addToCartAjax(productId, button) {
    var originalHTML = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'add-to-cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        button.disabled = false;
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            if (data.success) {
                var countEl = document.getElementById('cart-count');
                countEl.textContent = data.cart_count;
                countEl.classList.add('bump');
                setTimeout(function () { countEl.classList.remove('bump'); }, 300);

                button.innerHTML = '<i class="bx bx-check"></i> Added';
                setTimeout(function () { button.innerHTML = originalHTML; }, 1200);
            } else {
                button.innerHTML = originalHTML;
                alert(data.message || 'Could not add product to cart.');
            }
        } else {
            button.innerHTML = originalHTML;
        }
    };
    xhr.send('product_id=' + encodeURIComponent(productId) + '&ajax=1');
}
