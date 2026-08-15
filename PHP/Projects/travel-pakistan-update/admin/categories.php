<?php

// ===============Include the admin session=========

include "../includes/admin-session.php";

$page_title = "Category Management";
$dash_role = "admin";
include "../includes/dash-header.php";
$active = "categories";

include "../config/database.php";

// ==============Fetch All Categories======================================

$query = "SELECT * FROM category ORDER BY category_id DESC";

$result = mysqli_query($conn, $query);

?>

<div class="dash-shell">
    <?php include "../includes/admin-sidebar.php"; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">Category Management</h2>
                <p class="mb-0">Travel types used to tag destinations across every province.</p>
            </div>
             <button class="btn btn-teal btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-lg"></i>
                Add Category
            </button>
        </div>

        <div class="panel-tp">
            <div class="table-responsive">
                <table class="table table-tp mb-0" id="categoriesTable">
                   <thead>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = mysqli_fetch_assoc($result)){
                        ?>
                        <tr data-category-id="<?= $row["category_id"]; ?>"
                            data-status="<?= strtolower($row["category_status"]); ?>">
                            <td><?= htmlspecialchars($row["category_title"]); ?> </td>
                            <td> <?= htmlspecialchars($row["category_description"]); ?> </td>
                            <td>
                                <?php
                                if($row["category_status"] == "Active"){
                                ?>
                                    <span class="status-pill active">Active </span>
                                <?php
                                }
                                else{
                                ?>
                                    <span class="status-pill inactive"> InActive </span>
                                <?php
                                }
                                ?>
                            </td>

                            <td><?= date("d M Y", strtotime($row["created_at"])); ?></td>

                            <td class="text-end">

                                <!-- Edit -->

                                <button class="btn btn-sm btn-ghost btn-edit-category" data-id="<?= $row["category_id"]; ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal"
                                    onclick="loadCategory(this.getAttribute('data-id'));">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <?php

                                if($row["category_status"] == "Active"){
                                ?>
                                    <button
                                        class="btn btn-sm btn-danger-outline btn-deactivate-category"
                                        data-id="<?= $row["category_id"]; ?>"
                                        onclick="updateCategoryStatus(this, 'InActive');">
                                        Deactivate
                                    </button>
                                <?php
                                }
                                else{
                                ?>
                                    <button
                                        class="btn btn-sm btn-teal btn-activate-category"
                                        data-id="<?= $row["category_id"]; ?>"
                                        onclick="updateCategoryStatus(this, 'Active');">
                                        Activate
                                    </button>
                                <?php

                                }

                                ?>

                                <button
                                    class="btn btn-sm btn-danger-outline btn-delete-category"
                                    data-id="<?= $row["category_id"]; ?>"
                                    onclick="deleteCategory(this);">
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

<!-- ================Add Category Modal=========================== -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content" style="border-radius:var(--radius-md);border:none;">

            <div class="modal-header"> <h5 class="modal-title"> Add Category  </h5>
                <button  type="button" class="btn-close" data-bs-dismiss="modal"> </button>
            </div>

            <div class="modal-body form-tp">
                <div id="addCategoryAlert" class="form-alert"></div>

                <form id="addCategoryForm" novalidate>

                    <div class="mb-3">
                        <label class="form-label"> Category Name </label>
                        <input type="text" class="form-control" id="categoryTitle">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"> Description</label>
                        <textarea class="form-control" id="categoryDescription" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"> Status </label>
                        <select class="form-select" id="categoryStatus">
                            <option value="Active">Active</option>
                            <option value="InActive">InActive</option>
                        </select>
                    </div>
               
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-ghost" data-bs-dismiss="modal"> Cancel </button>
                <button class="btn btn-teal" id="addCategoryBtn" onclick="submitCategory();"> Save Category </button>
            </div>

        </div>

    </div>

</div>

<!-- ==========================Edit Category Modal===================== -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content" style="border-radius:var(--radius-md);border:none;">

            <div class="modal-header">
                <h5 class="modal-title"> Edit Category </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"> </button>
            </div>

            <div class="modal-body form-tp">
                <div id="editCategoryAlert" class="form-alert"> </div>

                <form id="editCategoryForm" novalidate>

                    <input type="hidden" id="editCategoryId">
                    <div class="mb-3">
                        <label class="form-label"> Category Name </label>
                        <input  type="text" class="form-control" id="editCategoryTitle">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"> Description </label>
                        <textarea class="form-control"  id="editCategoryDescription" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">  Status </label>
                        <select  class="form-select" id="editCategoryStatus">
                            <option value="Active">Active</option>
                            <option value="InActive">InActive</option>
                        </select>
                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-ghost" data-bs-dismiss="modal"> Cancel </button>
                <button class="btn btn-teal" id="updateCategoryBtn" onclick="updateCategory();"> Update Category </button>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ajax/categories_ajax.js"></script>
</body>
</html>
