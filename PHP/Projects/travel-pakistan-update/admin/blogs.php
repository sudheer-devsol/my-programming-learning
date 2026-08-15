<?php

    // ========Database Connection=========
    include "../config/database.php";

    // ========Admin Session=========
    include "../includes/admin-session.php";

    $page_title = "Blog Management";
    $dash_role = "admin";

    include "../includes/dash-header.php";
    $active = "blogs";

    $query = "SELECT * FROM blog ORDER BY blog_id DESC";
    $result = mysqli_query($conn, $query);

?>

<div class="dash-shell">
<?php include "../includes/admin-sidebar.php"; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">Blog Management</h2>
                <p class="mb-0">Each blog represents one province. Only admins can add or edit blogs.</p>
                <?php if(isset($_GET["mesg"])){ ?>
                    <div class="form-alert" style="display:block;"><?= htmlspecialchars($_GET["mesg"]); ?></div>
                <?php } ?>
            </div>
            <button class="btn btn-teal btn-sm" data-bs-toggle="modal" data-bs-target="#blogModal" id="openAddBlog" onclick="openAddBlogModal();"><i class="bi bi-plus-lg"></i> Add Blog</button>
        </div>
        <div class="panel-tp">
             <div class="panel-head">
                <div class="d-flex gap-2 flex-wrap">
                    <select class="form-select form-tp" id="blogStatusFilter"  style="max-width:220px;" onchange="applyBlogFilters();">
                        <option value="all"> All Blogs </option>
                        <option value="Active">  Active Blogs </option>
                        <option value="InActive"> InActive Blogs </option>
                    </select>
                </div>
                <input type="text" class="form-control form-tp" id="blogSearch" placeholder="Search blogs..." style="max-width:220px;" onkeyup="applyBlogFilters();">
            </div>

            <div class="table-responsive">
                <table class="table table-tp mb-0">
                    <thead><tr><th>Province</th><th>Posts / Page</th><th>Status</th><th>Created</th><th class="text-end">Actions</th></tr></thead>
                    <tbody>
                            <?php
                                while($row = mysqli_fetch_assoc($result)) { 
                                
                                $image = !empty($row["blog_background_image"]) ? $row["blog_background_image"] : "default-blog.jpg"; ?>

                                <tr data-blog-id="<?= $row["blog_id"]; ?>">
                                    <td class="d-flex align-items-center gap-2">
                                        <img src="../assets/images/blogs/<?= htmlspecialchars($image); ?>"
                                        style="width:40px;height:40px;border-radius:8px;object-fit:cover;">
                                        <?= htmlspecialchars($row["blog_title"]); ?>
                                    </td>
                            <td>
                                <?= $row["post_per_page"]; ?>
                            </td>

                            <td>
                                <?php if($row["blog_status"]=="Active"){ ?>
                                    <span class="status-pill active"> Active </span>
                                <?php } else { ?>
                                    <span class="status-pill inactive"> InActive </span>
                                <?php } ?>
                            </td>
                            <td> <?= date("d M Y",strtotime($row["created_at"])); ?> </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-ghost btn-edit-blog" onclick="loadBlog(this.closest('tr').getAttribute('data-blog-id'));">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <?php if($row["blog_status"]=="Active"){ ?>
                                    <button  class="btn btn-sm btn-danger-outline btn-deactivate-blog" onclick="updateBlogStatus(this, 'deactivate');">  Deactivate </button>
                                <?php } else { ?>
                                    <button class="btn btn-sm btn-teal btn-activate-blog" onclick="updateBlogStatus(this, 'activate');"> Activate </button>
                                <?php } ?>

                                <button class="btn btn-sm btn-danger-outline btn-delete-blog" onclick="deleteBlog(this);">
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

<!-- Add/Edit Blog Modal -->
<div class="modal fade" id="blogModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">
    
        <div class="modal-content" style="border-radius:var(--radius-md);border:none;">
    
            <div class="modal-header">
                <h5 class="modal-title" id="blogModalTitle">Add Blog</h5>
                <button type="button" class="btn-close"  data-bs-dismiss="modal"></button></div>
            <div class="modal-body form-tp">

                <div id="blogModalAlert" class="form-alert"></div>
                
                <form id="blogForm" novalidate method="POST" enctype="multipart/form-data" action="../process/blogs_process.php" onsubmit="return validateBlogForm();">
                   
                    <input type="hidden" name="action" id="blogFormAction" value="add_blog">
                    <input type="hidden" name="blog_id" id="blogId" value="">
                    
                    <div class="row g-3">
                       
                        <div class="col-12">
                            <label>Province / Blog Title</label>
                            <input type="text" class="form-control" id="blogTitle" name="blog_title">
                        </div>
                        
                        <div class="col-md-6">
                            <label>Posts Per Page</label>
                            <input type="number" class="form-control" id="blogPostsPerPage" name="post_per_page" value="10">
                        </div>
                        
                        <div class="col-md-6">
                            <label>Status</label>
                            <select class="form-select" id="blogStatus" name="blog_status">
                                <option value="Active">Active</option>
                                <option value="InActive">Inactive</option>
                            </select>
                        </div>
                        
                        <div class="col-12"><label>Background Image</label>
                            <input type="file" class="form-control" id="blogImage" name="blog_background_image" accept="image/*">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="blogForm" class="btn btn-teal" id="blogSubmitBtn">Save Blog</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ajax/blogs_ajax.js"></script>

</body>
</html>
