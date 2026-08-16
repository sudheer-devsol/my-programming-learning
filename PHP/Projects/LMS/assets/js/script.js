// ===================== Shared JS (no frameworks, no jQuery) =====================

// Toggle sidebar on mobile
function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");
    if (sidebar) {
        sidebar.classList.toggle("show");
    }
}

// Simple required-field validation for a form (client side only, server always re-validates)
function validateForm(formId) {
    var form = document.getElementById(formId);
    var inputs = form.querySelectorAll("[required]");
    var valid = true;

    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].value.trim() == "") {
            inputs[i].classList.add("is-invalid");
            valid = false;
        } else {
            inputs[i].classList.remove("is-invalid");
        }
    }

    return valid;
}

// Simple email format check
function isValidEmail(email) {
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
}

// Confirm before delete actions
function confirmDelete(message) {
    return confirm(message ? message : "Are you sure you want to delete this record?");
}

// Show a temporary alert message inside a container
function showMessage(containerId, message, type) {
    var container = document.getElementById(containerId);
    if (!container) {
        return;
    }
    var cssClass = type == "error" ? "alert-danger" : "alert-success";
    container.innerHTML = '<div class="alert ' + cssClass + '">' + message + '</div>';

    setTimeout(function () {
        container.innerHTML = "";
    }, 4000);
}
