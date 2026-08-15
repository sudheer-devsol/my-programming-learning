<?php

// ==========================================
// Include Admin Session
// ==========================================

include "../includes/admin-session.php";

$page_title = "Add Post";
$dash_role = "admin";

include "../includes/dash-header.php";

$active = "posts";

include "../config/database.php";


// ==============Show All Active Blogs============================

$query = "SELECT blog.blog_id, blog.blog_title FROM blog WHERE blog.blog_status = 'Active' ORDER BY blog.blog_title ASC";

$blog_result = mysqli_query($conn, $query);


// ===============Show All Active Categories===========================

$query = "SELECT category.category_id, category.category_title FROM category 
WHERE category.category_status = 'Active' ORDER BY category.category_title ASC";

$category_result = mysqli_query($conn, $query);



// ==============Default Values============================

$post = null;
$selected_categories = array();
$gallery_images = array();



// ============Edit Post==============================

if(isset($_GET["id"]))
{
    $post_id = $_GET["id"];


    //================ Get Post Detaila==========================

    $query = "SELECT post.post_id, post.blog_id,  post.post_title, post.post_summary, post.post_description,
    post.featured_image, post.post_status, post.is_comment_allowed
    FROM post WHERE post.post_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){

        $post = mysqli_fetch_assoc($result);
    }


    
    // =====================Get Selected Categories=====================
    
    $query = "SELECT post_category.category_id
    FROM post_category WHERE post_category.post_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while($row = mysqli_fetch_assoc($result)){

        $selected_categories[] = $row["category_id"];
    }


    
    //================Get Gallery Images==========================
    
    $query = "SELECT post_atachment.post_attachment_path FROM post_atachment
    WHERE post_atachment.post_id = ? ORDER BY post_atachment.post_atachment_id ASC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $gallery_result = mysqli_stmt_get_result($stmt);

    while($row = mysqli_fetch_assoc($gallery_result))
    {
        $gallery_images[] = $row;
    }
}

$mesg = isset($_GET["mesg"]) ? htmlspecialchars($_GET["mesg"]) : "";
?>



<div class="dash-shell">
    <?php include "../includes/admin-sidebar.php"; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">
                <?= ($post) ? "Edit Post" : "Add Post"; ?>
                </h2>

                <p class="mb-0">
                <?= ($post)
                ? "Update an existing destination, attraction, or travel guide."
                : "Publish a new destination, attraction, or travel guide.";
                ?>
                </p>
            </div>
            <a href="posts.php" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Back to Posts</a>
        </div>

        <div id="postFormAlert" class="form-alert" <?= $mesg ? '' : 'style="display:none;"'; ?>><?= $mesg; ?></div>

        <form id="postForm" class="form-tp" method="POST" enctype="multipart/form-data" action="../process/posts_process.php" novalidate onsubmit="return validatePostForm();">
            <input type="hidden" name="action" value="<?= ($post) ? "update_post" : "add_post"; ?>">
            <input type="hidden" name="post_id" id="postId" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '' ?>">

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="panel-tp mb-4">
                        <div class="panel-body">
                            <div class="mb-3">
                                <label>Post Title</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="postTitle"
                                    name="post_title"
                                    value="<?= ($post) ? htmlspecialchars($post["post_title"]) : ""; ?>">
                                <div class="field-error" id="err-postTitle">Title is required.</div>
                            </div>
                            <div class="mb-3">
                                <label>Summary</label>
                                <textarea
                                    class="form-control"
                                    id="postSummary"
                                    name="post_summary"
                                    rows="2"><?= ($post) ? htmlspecialchars($post["post_summary"]) : ""; ?></textarea>
                                <div class="field-error" id="err-postSummary">Summary is required.</div>
                            </div>
                            <div class="mb-0">
                                <label>Full Description</label>
                                <textarea
                                    class="form-control"
                                    id="postDescription"
                                    name="post_description"
                                    rows="10"><?= ($post) ? htmlspecialchars($post["post_description"]) : ""; ?></textarea>
                                <div class="field-error" id="err-postDescription">Description is required.</div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-tp">
                        <div class="panel-head"><strong>Gallery Attachments</strong></div>
                        <div class="panel-body">
                            <input type="file" class="form-control" id="postGalleryImages" name="gallery_images[]" accept="image/*" multiple onchange="previewGalleryImages(this);">
                           <div
                                id="galleryPreview"
                                class="row g-2 mt-2">

                            <?php

                            foreach($gallery_images as $image)
                            {
                            ?>
                            <div class="col-3">
                                <img
                                    src="../assets/images/posts/<?= htmlspecialchars($image["post_attachment_path"]); ?>"
                                    class="img-fluid rounded">
                            </div>

                            <?php
                            }
                            ?>
                            </div>
                            <p class="mt-2 mb-0" style="font-size:.82rem;">Uploads map to the <code>post_atachment</code> table.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="panel-tp mb-4">
                        <div class="panel-body">
                            <div class="mb-3">
                                <label>Province (Blog)</label>
                                <select class="form-select" id="postBlogId" name="blog_id">
                                    <?php
                                    mysqli_data_seek($blog_result,0);
                                    while($row = mysqli_fetch_assoc($blog_result)){

                                    ?>

                                        <option value="<?= htmlspecialchars($row["blog_id"]); ?>"

                                        <?= ($post && $post["blog_id"] == $row["blog_id"]) ? "selected" : ""; ?>>

                                        <?= htmlspecialchars($row["blog_title"]); ?>

                                        </option>

                                    <?php

                                    }

                                    ?>

                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Categories</label>
                                <select class="form-select" id="postCategories" name="category_ids[]" multiple size="6">
                                    <?php

                                    mysqli_data_seek($category_result,0);

                                    while($row = mysqli_fetch_assoc($category_result)){

                                    ?>

                                    <option

                                    value="<?= htmlspecialchars($row["category_id"]); ?>"

                                    <?= in_array($row["category_id"],$selected_categories) ? "selected" : ""; ?>>

                                    <?= htmlspecialchars($row["category_title"]); ?>

                                    </option>

                                    <?php

                                    }

                                    ?>

                                    </select>
                                <p class="mt-1 mb-0" style="font-size:.8rem;color:var(--ink-soft);">Ctrl/Cmd-click to select multiple.</p>
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select class="form-select" id="postStatus" name="post_status">
                                    <option value="Active"
                                        <?= ($post && $post["post_status"]=="Active") ? "selected" : ""; ?>> Active
                                    </option>

                                    <option value="InActive"
                                        <?= ($post && $post["post_status"]=="InActive") ? "selected" : ""; ?>> InActive
                                    </option>
                                </select>
                            </div>
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_comment_allowed" value="0">
                                <input class="form-check-input" type="checkbox" id="postCommentsAllowed" name="is_comment_allowed" value="1"
                                    <?= (!$post || $post["is_comment_allowed"]==1) ? "checked" : ""; ?>>
                                <label class="form-check-label" for="postCommentsAllowed" style="text-transform:none;font-family:var(--font-body);font-weight:400;">Allow comments on this post</label>
                            </div>
                        </div>
                    </div>

                    <div class="panel-tp">
                        <div class="panel-body">
                            <label>Featured Image</label>
                            <input type="file" class="form-control mb-3" id="postFeaturedImage" name="featured_image" accept="image/*" onchange="previewFeaturedImage(this);">
                            <?php
                                if($post && $post["featured_image"] != "")
                                {
                                ?>
                                <img  id="featuredPreview"
                                    src="../assets/images/posts/<?= htmlspecialchars($post["featured_image"]); ?>"
                                    style=" display:block;  max-width:100%; margin-top:10px; border-radius:8px;">
                                <?php

                                }
                                else
                                {

                                ?>

                                <img id="featuredPreview"
                                    style="display:none; max-width:100%; margin-top:10px; border-radius:8px;">
                                <?php

                                }

                                ?>
                            <button  type="submit"  class="btn btn-teal w-100"  id="postSubmitBtn">
                                <?= ($post) ? "Update Post" : "Publish Post"; ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ajax/edit_post_ajax.js"></script>

</body>
</html>
