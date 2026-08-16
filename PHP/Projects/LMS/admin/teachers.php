<?php
require_once "../includes/functions.php";
require_role("admin");

$page_title = "Manage Teachers";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_admin.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="section-title mb-0">All Teachers</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal"><i class="fa-solid fa-plus"></i> Add Teacher</button>
    </div>

    <div id="formMessage"></div>

    <div class="stat-card">
        <div class="table-responsive">
        <table class="table table-hover align-middle" id="teacherTable">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
            <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM user WHERE role = 'teacher' ORDER BY user_id DESC");
            if (mysqli_num_rows($result) == 0) {
                echo '<tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-chalkboard-user"></i>No teachers yet.</div></td></tr>';
            }
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr id='row" . $row["user_id"] . "'>";
                echo "<td>" . $i++ . "</td>";
                echo "<td>" . htmlspecialchars($row["first_name"] . " " . $row["last_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                $badge = $row["status"] == "active" ? "success" : "secondary";
                echo '<td><span class="badge bg-' . $badge . '">' . $row["status"] . '</span></td>';
                echo "<td>" . format_date($row["created_at"]) . "</td>";
                echo '<td>
                        <button class="btn btn-sm btn-outline-warning" onclick="toggleStatus(' . $row["user_id"] . ', \'' . $row["status"] . '\')"><i class="fa-solid fa-toggle-on"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(' . $row["user_id"] . ')"><i class="fa-solid fa-trash"></i></button>
                      </td>';
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Add Teacher</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form id="addTeacherForm" onsubmit="return addTeacher(event)">
      <div class="modal-body">
        <div id="modalMessage"></div>
        <div class="row">
            <div class="col-6 mb-3"><label class="form-label">First Name</label><input type="text" class="form-control" id="t_first_name" required></div>
            <div class="col-6 mb-3"><label class="form-label">Last Name</label><input type="text" class="form-control" id="t_last_name" required></div>
        </div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="t_email" required></div>
        <div class="mb-3"><label class="form-label">Phone</label><input type="text" class="form-control" id="t_phone"></div>
        <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" id="t_password" required></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Teacher</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php include "../includes/foot.php"; ?>
<script>
function addTeacher(event) {
    event.preventDefault();
    var firstName = document.getElementById("t_first_name").value.trim();
    var lastName = document.getElementById("t_last_name").value.trim();
    var email = document.getElementById("t_email").value.trim();
    var phone = document.getElementById("t_phone").value.trim();
    var password = document.getElementById("t_password").value;

    if (firstName == "" || lastName == "" || email == "" || password == "") {
        showMessage("modalMessage", "Please fill all required fields.", "error");
        return false;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/user_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText.trim();
            if (response == "success") {
                window.location.reload();
            } else if (response == "duplicate") {
                showMessage("modalMessage", "A user with this email already exists.", "error");
            } else {
                showMessage("modalMessage", "Something went wrong.", "error");
            }
        }
    };
    xhr.send("action=add_user&role=teacher&first_name=" + encodeURIComponent(firstName) +
        "&last_name=" + encodeURIComponent(lastName) + "&email=" + encodeURIComponent(email) +
        "&phone=" + encodeURIComponent(phone) + "&password=" + encodeURIComponent(password));
    return false;
}

function toggleStatus(userId, currentStatus) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/user_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            window.location.reload();
        }
    };
    xhr.send("action=toggle_status&user_id=" + userId + "&current_status=" + currentStatus);
}

function deleteUser(userId) {
    if (!confirmDelete("Delete this teacher? This will also remove their courses.")) {
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/user_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText.trim();
            if (response == "success") {
                var row = document.getElementById("row" + userId);
                if (row) { row.remove(); }
            } else {
                alert("Could not delete this teacher.");
            }
        }
    };
    xhr.send("action=delete_user&user_id=" + userId);
}
</script>
