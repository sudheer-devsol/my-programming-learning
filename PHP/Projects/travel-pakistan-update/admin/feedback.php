<?php

// ==========================================
// Include Admin Session
// ==========================================

include "../includes/admin-session.php";

$page_title = "Feedback Management";
$dash_role = "admin";

include "../includes/dash-header.php";

$active = "feedback";

include "../config/database.php";

/*
==========================================
Show All Feedback
==========================================
*/

$query = "SELECT user_feedback.feedback_id, user_feedback.user_name, user_feedback.user_email, user_feedback.feedback,
user_feedback.created_at FROM user_feedback ORDER BY user_feedback.feedback_id DESC";

$result = mysqli_query($conn,$query);

?>
<div class="dash-shell">
    <?php include "../includes/admin-sidebar.php"; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">Feedback Management</h2>
                <p class="mb-0">Messages submitted through the public Contact page.</p>
            </div>
        </div>

        <div class="panel-tp">
            <div class="panel-head">
                <input type="text" class="form-control form-tp" style="max-width:260px;" id="feedbackSearch" placeholder="Search by name or email..." onkeyup="filterFeedback();">
            </div>
            <div class="table-responsive">
                <table class="table table-tp mb-0" id="feedbackTable">
                    <thead><tr><th>Name</th><th>Email</th><th>Message</th><th>Date</th><th class="text-end">Actions</th></tr></thead>
              <tbody>

                <?php

                while($row = mysqli_fetch_assoc($result)){

                ?>
                <tr data-feedback-id="<?= htmlspecialchars($row["feedback_id"]); ?>">
                    <!-- Name -->
                    <td> <?= htmlspecialchars($row["user_name"]); ?> </td>

                    <!-- Email -->
                    <td> <?= htmlspecialchars($row["user_email"]); ?> </td>


                    <!-- Feedback -->

                    <td style="max-width:320px;"> <?= htmlspecialchars($row["feedback"]); ?> </td>

                    <!-- Date -->

                    <td> <?= date("d M Y",strtotime($row["created_at"])); ?> </td>

                    <!-- Actions -->

                    <td class="text-end">
                        <button class="btn btn-sm btn-danger-outline btn-delete-feedback"
                         data-id="<?= htmlspecialchars($row["feedback_id"]); ?>"
                         onclick="deleteFeedback(this);">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php
                }
                ?>
                </tbody>
                </table>
            </div>
        </div>
    </main>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ajax/feedback_ajax.js"></script>

</body>
</html>