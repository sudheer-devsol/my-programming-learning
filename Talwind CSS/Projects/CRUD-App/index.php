<?php
require 'config.php';
require 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Management System</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen text-gray-800">

<div class="max-w-4xl mx-auto px-4 py-10">

    <h1 class="text-2xl font-semibold mb-6">Student Management System</h1>

    <!-- Add / Update form -->
    <form id="student-form" class="bg-white border border-gray-200 rounded-lg p-5 mb-8 shadow-sm">
        <input type="hidden" id="student-id" name="id" value="">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="name">Name</label>
                <input type="text" id="name" name="name" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="email">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="phone">Phone</label>
                <input type="text" id="phone" name="phone"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="course">Course</label>
                <input type="text" id="course" name="course"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" id="submit-btn"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded">
                Add Student
            </button>
            <button type="button" id="cancel-btn"
                    class="hidden text-sm text-gray-500 hover:text-gray-700">
                Cancel
            </button>
        </div>
    </form>

    <!-- Students table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="py-2 px-3">Name</th>
                    <th class="py-2 px-3">Email</th>
                    <th class="py-2 px-3">Phone</th>
                    <th class="py-2 px-3">Course</th>
                    <th class="py-2 px-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="students-tbody">
                <?php echo renderStudentRows($conn); ?>
            </tbody>
        </table>
    </div>

</div>

<script>
// ---- Plain, core JavaScript AJAX (fetch) — no jQuery, no DataTables, no JSON ----

var form       = document.getElementById('student-form');
var idField    = document.getElementById('student-id');
var nameField  = document.getElementById('name');
var emailField = document.getElementById('email');
var phoneField = document.getElementById('phone');
var courseField= document.getElementById('course');
var submitBtn  = document.getElementById('submit-btn');
var cancelBtn  = document.getElementById('cancel-btn');
var tbody      = document.getElementById('students-tbody');

function resetForm() {
    form.reset();
    idField.value = '';
    submitBtn.textContent = 'Add Student';
    cancelBtn.classList.add('hidden');
}

// Handle Add / Update submit
form.addEventListener('submit', function (e) {
    e.preventDefault();

    var action = idField.value ? 'update' : 'add';

    var data = new FormData();
    data.append('action', action);
    data.append('id', idField.value);
    data.append('name', nameField.value.trim());
    data.append('email', emailField.value.trim());
    data.append('phone', phoneField.value.trim());
    data.append('course', courseField.value.trim());

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'actions.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            tbody.innerHTML = xhr.responseText; // server sends back plain HTML rows
            resetForm();
        }
    };
    xhr.send(data);
});

// Handle Edit / Delete clicks (event delegation on the table body)
tbody.addEventListener('click', function (e) {
    var row = e.target.closest('tr');
    if (!row) return;

    if (e.target.classList.contains('edit-btn')) {
        idField.value     = row.getAttribute('data-id');
        nameField.value   = row.getAttribute('data-name');
        emailField.value  = row.getAttribute('data-email');
        phoneField.value  = row.getAttribute('data-phone');
        courseField.value = row.getAttribute('data-course');

        submitBtn.textContent = 'Update Student';
        cancelBtn.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    if (e.target.classList.contains('delete-btn')) {
        if (!confirm('Delete this student?')) return;

        var id = row.getAttribute('data-id');
        var data = new FormData();
        data.append('action', 'delete');
        data.append('id', id);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'actions.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                tbody.innerHTML = xhr.responseText;
            }
        };
        xhr.send(data);
    }
});

cancelBtn.addEventListener('click', resetForm);
</script>

</body>
</html>
