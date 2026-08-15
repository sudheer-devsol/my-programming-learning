<?php

// ===============Include the admin session=========

include "../includes/admin-session.php";

$page_title = "Post Management";
$dash_role = "admin";
include "../includes/dash-header.php";
$active = "posts";

include "../config/database.php";


//===========Show All Blogs===============================

$query = "SELECT blog.blog_id, blog.blog_title FROM blog WHERE blog.blog_status = 'Active'
ORDER BY blog.blog_title ASC";

$blog_result = mysqli_query($conn, $query);

// ==========Show All Posts=======================

$query = " SELECT post.*, blog.blog_title FROM post
INNER JOIN blog
ON post.blog_id = blog.blog_id
ORDER BY post.post_id DESC";

$result = mysqli_query($conn,$query);

?>


<div class="dash-shell">
    <?php include "../includes/admin-sidebar.php"; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">Post Management</h2>
                <p class="mb-0">Only admins can publish destinations, guides, and articles.</p>
            </div>
            <a href="post-edit.php" class="btn btn-teal btn-sm"><i class="bi bi-plus-lg"></i> Add Post</a>
        </div>

        <div class="panel-tp">
            <div class="panel-head">
                <select  class="form-select form-tp" style="max-width:200px;"  id="blogFilterSelect" onchange="applyPostFilters();">
                        <option value="all">All Provinces</option>
                        <?php
                        while($blog = mysqli_fetch_assoc($blog_result)){
                        ?>
                            <option value="<?= htmlspecialchars($blog["blog_title"]); ?>">
                                <?= htmlspecialchars($blog["blog_title"]); ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                <input type="text" class="form-control form-tp" style="max-width:220px;" id="postSearch" placeholder="Search posts..." onkeyup="applyPostFilters();">
            </div>
        
            <div class="table-responsive">
                <table class="table table-tp mb-0" id="postsTable">
                    <thead><tr><th>Post</th><th>Province</th><th>Status</th><th>Comments</th><th>Published</th><th class="text-end">Actions</th></tr></thead>
                    <tbody>
                        <?php
                        while($row = mysqli_fetch_assoc($result)){
                        ?>

                        <tr data-post-id="<?= $row["post_id"]; ?>"

                             data-blog="<?= htmlspecialchars($row["blog_title"]); ?>">

                            <!-- Post -->

                            <td class="d-flex align-items-center gap-2">
                                <img src="../assets/images/posts/<?= $row["featured_image"]; ?>"
                                style=" width:40px; height:40px; border-radius:8px; object-fit:cover;">
                                <?= htmlspecialchars($row["post_title"]); ?>
                            </td>


                            <!-- Province -->

                            <td><?= htmlspecialchars($row["blog_title"]); ?></td>


                            <!-- Status -->

                            <td>
                                <?php
                                if($row["post_status"]=="Active"){

                                    echo '<span class="status-pill active">Active</span>';
                                }
                                else{
                                    
                                    echo '<span class="status-pill inactive">Inactive</span>';
                                }
                                ?>
                            </td>


                            <!-- Comments -->

                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-comments" type="checkbox" onchange="updateComments(this);"
                                    <?= ($row["is_comment_allowed"]==1) ? "checked" : ""; ?>>
                                </div>
                            </td>


                            <!-- Published -->

                            <td>

                                <?= date("d M Y",strtotime($row["created_at"])); ?>

                            </td>


                            <!-- Actions -->

                            <td class="text-end">
                                <a href="post-edit.php?id=<?= $row["post_id"]; ?>"  class="btn btn-sm btn-ghost">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <?php
                                if($row["post_status"]=="Active"){
                                ?>
                                    <button class="btn btn-sm btn-danger-outline btn-deactivate-post"  data-id="<?= $row["post_id"]; ?>" onclick="updatePostStatus(this, 'deactivate');">
                                        Deactivate
                                    </button>
                                <?php

                                }
                                else{
                                ?>
                                    <button  class="btn btn-sm btn-teal btn-activate-post" data-id="<?= $row["post_id"]; ?>" onclick="updatePostStatus(this, 'activate');">
                                        Activate
                                    </button>

                                <?php

                                }

                                ?>

                                <button class="btn btn-sm btn-danger-outline btn-delete-post" data-id="<?= $row["post_id"]; ?>" onclick="deletePost(this);">
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
<script src="../ajax/posts_ajax.js"></script>


</body>
</html>