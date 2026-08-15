<?php
// ===============Include the admin session=========
include "../includes/admin-session.php";

$page_title = "Comment Management";
$dash_role = "admin";
include "../includes/dash-header.php";
$active = "comments";

// ===============Connection=========
include "../config/database.php";

//================show all comments==========================
$query = "SELECT post_comment.post_comment_id, post_comment.comment, post_comment.is_active, post_comment.created_at, user.user_id, user.first_name, user.last_name, user.user_image, post.post_id, post.post_title FROM post_comment INNER JOIN user ON post_comment.user_id = user.user_id INNER JOIN post ON post_comment.post_id = post.post_id
    ORDER BY post_comment.post_comment_id DESC";

$result = mysqli_query($conn,$query);
?>

<div class="dash-shell">
    <?php include "../includes/admin-sidebar.php"; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">Comment Management</h2>
                <p class="mb-0">Review, activate, or remove visitor reviews on any post.</p>
            </div>
        </div>

        <div class="panel-tp">
            <div class="panel-head">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-sm btn-teal comment-filter-chip active" data-status="all" onclick="setCommentFilter(this);">All</button>
                    <button class="btn btn-sm btn-ghost comment-filter-chip" data-status="active" onclick="setCommentFilter(this);">Active</button>
                    <button class="btn btn-sm btn-ghost comment-filter-chip" data-status="inactive" onclick="setCommentFilter(this);">Disabled</button>
                </div>
                <input type="text" class="form-control form-tp" style="max-width:220px;" id="commentSearch" placeholder="Search comments..." onkeyup="applyCommentFilters();">
            </div>

            <div class="table-responsive">
                <table class="table table-tp mb-0" id="commentsTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Comment</th>
                            <th>Post</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)){ ?>
                        <tr data-comment-id="<?= $row["post_comment_id"]; ?>"
                            data-status="<?= strtolower(htmlspecialchars($row["is_active"])); ?>">

                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if($row["user_image"] != ""){ ?>
                                        <img src="../assets/images/users/<?= htmlspecialchars($row["user_image"]); ?>"
                                            style="width:32px; height:32px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                                    <?php } else { ?>
                                        <img src="../assets/images/default-user.png"
                                            style="width:32px; height:32px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                                    <?php } ?>
                                    <div>
                                        <?= htmlspecialchars($row["first_name"]); ?> <?= htmlspecialchars($row["last_name"]); ?>
                                    </div>
                                </div>
                            </td>

                            <td style="max-width:280px;"> <?= htmlspecialchars($row["comment"]); ?></td>
                            
                            <td>
                                <a href="../post-details.php?id=<?= htmlspecialchars($row["post_id"]); ?>">
                                    <?= htmlspecialchars($row["post_title"]); ?>
                                </a>
                            </td>

                            <td>
                                <?php if($row["is_active"] == "Active"){ ?>
                                    <span class="status-pill active"> Active </span>
                                <?php } else { ?>
                                    <span class="status-pill inactive"> Disabled </span>
                                <?php } ?>
                            </td>

                            <td> <?= date("d M Y", strtotime($row["created_at"])); ?> </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <?php if($row["is_active"] == "Active"){ ?>
                                    <button class="btn btn-sm btn-danger-outline btn-toggle-comment" data-id="<?= htmlspecialchars($row["post_comment_id"]); ?>" onclick="toggleComment(this);">
                                        Disable
                                    </button>
                                <?php } else { ?>
                                    <button class="btn btn-sm btn-teal btn-toggle-comment" data-id="<?= htmlspecialchars($row["post_comment_id"]); ?>" onclick="toggleComment(this);">
                                        Activate
                                    </button>
                                <?php } ?>

                                <button class="btn btn-sm btn-danger-outline btn-delete-comment" data-id="<?= htmlspecialchars($row["post_comment_id"]); ?>" onclick="deleteComment(this);">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ajax/post_comments_ajax.js"></script>


</body>
</html>