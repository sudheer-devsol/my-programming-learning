<?php

// ===============Include the admin session=========
include "../includes/admin-session.php";

$page_title = "User Management";
$dash_role = "admin";
include "../includes/dash-header.php";

$active = "users";
// ===============Database Connection=========
include "../config/database.php";

// =============Fetch All Users=======================================
$query = "SELECT * FROM user WHERE role_id != 1 ORDER BY user_id DESC";
$result = mysqli_query($conn, $query);

?>
<div class="dash-shell">
<?php include "../includes/admin-sidebar.php"; ?>

    <main class="dash-main">
        <!-- ================Top Bar===================== -->

        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;"> User Management</h2>
                <p class="mb-0"> Approve, reject, and manage traveler accounts.</p>
                <?php if(isset($_GET["mesg"])){ ?>
                    <div class="form-alert" style="display:block;"><?= htmlspecialchars($_GET["mesg"]); ?></div>
                <?php } ?>
            </div>
        
            <button class="btn btn-teal btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-lg"></i>
                Add User
            </button>

        </div>

        <!-- ===============Users Table========================-->

        <div class="panel-tp">

            <!-- ===============Filters ===============-->
            <div class="panel-head">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-sm btn-teal status-filter-chip active" data-status="all" onclick="setUserFilter(this);"> All </button>
                    <button class="btn btn-sm btn-ghost status-filter-chip" data-status="pending" onclick="setUserFilter(this);"> Pending </button>
                    <button class="btn btn-sm btn-ghost status-filter-chip" data-status="active" onclick="setUserFilter(this);"> Active </button>
                    <button class="btn btn-sm btn-ghost status-filter-chip" data-status="inactive" onclick="setUserFilter(this);"> Inactive </button>
                    <button class="btn btn-sm btn-ghost status-filter-chip" data-status="rejected" onclick="setUserFilter(this);"> Rejected </button>
                </div>
                <input type="text" class="form-control form-tp" id="userSearch" placeholder="Search users..." style="max-width:220px;" onkeyup="applyUserFilters();">
            </div>

            <!-- ===============Table=============== -->
            <div class="table-responsive">
                <table class="table table-tp mb-0" id="usersTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = mysqli_fetch_assoc($result)){
                           
                        //=================User Status=========================
                            
                            if($row["is_approved"] == "Pending"){
                                $status = "pending";
                            }
                            else if($row["is_approved"] == "Rejected"){
                                $status = "rejected";
                            }
                            else if($row["is_active"] == "Active"){
                                $status = "active";
                            }
                            else{
                                $status = "inactive";
                            }
                        ?>
                        <tr data-user-id="<?= $row["user_id"]; ?>" data-status="<?= $status; ?>">
                        <!--=============== User Details ===============-->
                           <td>
                                <div class="d-flex align-items-center">
                                    <img src="../assets/images/users/<?= $row["user_image"]; ?>" class="rounded-circle me-3"
                                        width="45"
                                        height="45"
                                        style="object-fit:cover;">
                                    <div>
                                        <div class="fw-semibold"> <?= htmlspecialchars($row["first_name"]); ?> <?= htmlspecialchars($row["last_name"]); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td> <?= htmlspecialchars($row["email"]); ?></td>
                            <td> 
                                <?php
                                if($row["role_id"] == 1){
                                    echo "Admin";
                                }
                                else{
                                    echo "Traveler";
                                }
                                ?>
                            </td>

                            <td>
                                <?php
                                if($row["is_approved"] == "Pending"){
                                    echo '<span class="status-pill pending">Pending</span>';
                                }
                                else if($row["is_approved"] == "Approved"){
                                    echo '<span class="status-pill active">Approved</span>';
                                }
                                else{
                                    echo '<span class="status-pill rejected">Rejected</span>';
                                }
                                ?>
                            </td>

                            <td><?= date("d M Y", strtotime($row["created_at"])); ?></td>
                            
                            <!-- Actions -->

                            <td class="text-end"> 
                                <button class="btn btn-sm btn-ghost btn-edit-user" data-id="<?= $row["user_id"]; ?>" data-bs-toggle="modal" data-bs-target="#editUserModal" onclick="loadUser(this.getAttribute('data-id'));">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <?php
                                if($row["is_approved"] == "Pending"){
                                ?>
                                <button class="btn btn-sm btn-teal btn-approve-user" data-id="<?= $row["user_id"]; ?>" onclick="updateUserStatus(this, 'approve');">
                                    Approve
                                </button>

                                <button class="btn btn-sm btn-danger-outline btn-reject-user" data-id="<?= $row["user_id"]; ?>" onclick="updateUserStatus(this, 'reject');">
                                    Reject
                                </button>
                                <?php
                                }
                                else if($row["is_active"] == "Active"){
                                ?>
                                <button class="btn btn-sm btn-danger-outline btn-deactivate-user" data-id="<?= $row["user_id"]; ?>" onclick="updateUserStatus(this, 'deactivate');">
                                    Deactivate
                                </button>
                                <?php
                                }
                                else{
                                ?>
                                <button class="btn btn-sm btn-teal btn-activate-user" data-id="<?= $row["user_id"]; ?>" onclick="updateUserStatus(this, 'activate');">
                                    Activate
                                </button>
                                <?php
                                }
                                ?>
                                <button class="btn btn-sm btn-danger-outline btn-delete-user" data-id="<?= $row["user_id"]; ?>" onclick="deleteUser(this);">
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
<!--==================Add User Modal================================= -->

<div class="modal fade" id="addUserModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--radius-md);border:none;">
            <div class="modal-header">
                <h5 class="modal-title"> Add User </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body form-tp">
                <div id="addUserAlert" class="form-alert"></div>
                <form id="addUserForm" enctype="multipart/form-data" method="POST" action="../process/users_process.php" novalidate onsubmit="return validateAddUserForm();">
                    <input type="hidden" name="action" value="add_user">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label"> First Name </label>
                            <input type="text" class="form-control" id="auFirstName" name="first_name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Last Name </label>
                            <input type="text" class="form-control" id="auLastName" name="last_name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Email Address </label>
                            <input type="email" class="form-control" id="auEmail" name="email">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">  Password </label>
                            <input type="password"  class="form-control" id="auPassword" name="password">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Gender </label>
                            <select class="form-select" id="auGender" name="gender">
                                <option value=""> Select Gender </option>
                                <option value="Male"> Male </option>
                                <option value="Female"> Female</option>
                                <option value="Other"> Other </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Date of Birth </label>
                            <input type="date" class="form-control" id="auDob" name="dob">
                        </div>

                        <div class="col-12">
                            <label class="form-label"> Address </label>
                            <textarea class="form-control" id="auAddress" name="address" rows="3"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Profile Image</label>
                            <input type="file" class="form-control" id="auImage" name="user_image" accept="image/*">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> User Role </label>
                            <select class="form-select" id="auRole" name="role_id">
                                <option value="2"> Traveler </option>
                                <option value="1"> Admin </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">  Approval Status</label>
                            <select class="form-select" id="auApproval" name="is_approved">
                                <option value="Approved"> Approved </option> 
                                <option value="Pending">  Pending </option>
                                <option value="Rejected"> Rejected </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Account Status </label>
                            <select class="form-select" id="auStatus" name="is_active">
                                <option value="Active"> Active </option> 
                                <option value="InActive"> InActive</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button  type="button" class="btn btn-ghost" data-bs-dismiss="modal"> Cancel </button>
                <button type="submit" form="addUserForm" class="btn btn-teal" id="addUserSubmitBtn"> Save User </button>
            </div>
        </div>
    </div>
</div>

<!-- =============Edit User Modal=============================-->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--radius-md);border:none;">

            <div class="modal-header">
                <h5 class="modal-title"> Edit User </h5>
                <button type="button" class="btn-close"  data-bs-dismiss="modal"></button>
            </div>

            <!-- =================Table User Data================= -->
            <div class="modal-body form-tp">
                <div id="editUserAlert"  class="form-alert"> </div>
                <form id="editUserForm"  novalidate>
                    
                    <!-- Hidden User ID -->
                    <input type="hidden" id="editUserId">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"> First Name </label>
                            <input type="text" class="form-control" id="editFirstName">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Last Name </label>
                            <input type="text" class="form-control" id="editLastName">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Email </label>
                            <input type="email" class="form-control" id="editEmail">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">  Role  </label>
                            <select class="form-select"  id="editRole">
                                <option value="1"> Admin </option>
                                <option value="2"> Traveler </option>
                            </select>

                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Approval Status </label>
                            <select class="form-select" id="editApproval">
                                <option value="Pending"> Pending </option>
                                <option value="Approved"> Approved </option>
                                <option value="Rejected"> Rejected </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"> Active Status </label>
                            <select class="form-select" id="editStatus">
                                <option value="Active"> Active </option>
                                <option value="InActive"> InActive </option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-ghost" data-bs-dismiss="modal"> Cancel </button>
                <button type="button" class="btn btn-teal" id="updateUserBtn" onclick="updateUser();"> Update User </button>
            </div>
        
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ajax/users_ajax.js"></script>
</body>
</html> 