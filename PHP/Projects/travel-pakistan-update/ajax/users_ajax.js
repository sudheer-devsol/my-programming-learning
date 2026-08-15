/*
==============Admin Users Page - Filter/Search + CRUD (AJAX)============================
Note: Add User now submits as a normal HTML form
(method="POST" enctype="multipart/form-data") because it
uploads a profile image, so it does not use AJAX or
FormData. This file handles filtering, editing (no image),
status changes, and delete.
==============================================================
*/

function createXHR()
{
    var xhr = null;

    if(window.XMLHttpRequest)
    {
        xhr = new XMLHttpRequest();
    }
    else
    {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    return xhr;
}

// =============Filter Buttons=============================

function setUserFilter(chip)
{
    var chips = document.querySelectorAll(".status-filter-chip");

    for(var i = 0; i < chips.length; i++)
    {
        chips[i].classList.remove("btn-teal", "active");
        chips[i].classList.add("btn-ghost");
    }

    chip.classList.remove("btn-ghost");
    chip.classList.add("btn-teal", "active");

    applyUserFilters();
}

function applyUserFilters()
{
    var activeChip = document.querySelector(".status-filter-chip.active");
    var searchInput = document.getElementById("userSearch");
    var rows = document.querySelectorAll("#usersTable tbody tr");

    var status = activeChip.getAttribute("data-status");
    var term = searchInput.value.toLowerCase();

    for(var i = 0; i < rows.length; i++)
    {
        var row = rows[i];
        var rowStatus = row.getAttribute("data-status");
        var name = row.children[0].textContent.toLowerCase();

        var statusMatch = (status == "all" || rowStatus == status);
        var termMatch = name.indexOf(term) != -1;

        if(statusMatch && termMatch)
        {
            row.style.display = "";
        }
        else
        {
            row.style.display = "none";
        }
    }
}

// =============Validate Add User Form (before normal submit)=============================

function validateAddUserForm()
{
    var firstName = document.getElementById("auFirstName");
    var lastName = document.getElementById("auLastName");
    var email = document.getElementById("auEmail");
    var password = document.getElementById("auPassword");
    var addUserAlert = document.getElementById("addUserAlert");

    if(firstName.value.trim() == "" || lastName.value.trim() == "" || email.value.trim() == "" || password.value == "")
    {
        addUserAlert.className = "form-alert error";
        addUserAlert.style.display = "block";
        addUserAlert.textContent = "Please fill all required fields.";
        return false;
    }

    return true;
}

// =============Update User Status=============================

function updateUserStatus(btn, actionType)
{
    var row = btn.closest("tr");

    var xhr = createXHR();

    xhr.open("POST", "../process/users_process.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            if(xhr.responseText == "success")
            {
                location.reload();
            }
            else
            {
                alert(xhr.responseText);
            }
        }
    };

    var params = "action=update_status&status_action=" + encodeURIComponent(actionType) +
        "&user_id=" + encodeURIComponent(row.getAttribute("data-user-id"));

    xhr.send(params);
}

// ==================  Load User For Edit========================

function loadUser(userId)
{
    var xhr = createXHR();

    xhr.open("POST", "../process/users_process.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            // Response is plain text separated by "|"
            var parts = xhr.responseText.split("|");

            document.getElementById("editUserId").value = parts[0];
            document.getElementById("editFirstName").value = parts[1];
            document.getElementById("editLastName").value = parts[2];
            document.getElementById("editEmail").value = parts[3];
            document.getElementById("editRole").value = parts[4];
            document.getElementById("editApproval").value = parts[5];
            document.getElementById("editStatus").value = parts[6];
        }
    };

    xhr.send("action=get_user&user_id=" + encodeURIComponent(userId));
}

// =============Update User=============================

function updateUser()
{
    var editUserAlert = document.getElementById("editUserAlert");

    var xhr = createXHR();

    xhr.open("POST", "../process/users_process.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            if(xhr.responseText == "success")
            {
                location.reload();
            }
            else
            {
                editUserAlert.className = "form-alert error";
                editUserAlert.style.display = "block";
                editUserAlert.textContent = xhr.responseText;
            }
        }
    };

    var params = "action=update_user" +
        "&user_id=" + encodeURIComponent(document.getElementById("editUserId").value) +
        "&first_name=" + encodeURIComponent(document.getElementById("editFirstName").value.trim()) +
        "&last_name=" + encodeURIComponent(document.getElementById("editLastName").value.trim()) +
        "&email=" + encodeURIComponent(document.getElementById("editEmail").value.trim()) +
        "&role_id=" + encodeURIComponent(document.getElementById("editRole").value) +
        "&is_approved=" + encodeURIComponent(document.getElementById("editApproval").value) +
        "&is_active=" + encodeURIComponent(document.getElementById("editStatus").value);

    xhr.send(params);
}

// ==============  Delete User============================

function deleteUser(btn)
{
    if(!confirm("Delete this user permanently?"))
    {
        return;
    }

    var row = btn.closest("tr");

    var xhr = createXHR();

    xhr.open("POST", "../process/users_process.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            if(xhr.responseText == "success")
            {
                row.remove();
            }
            else
            {
                alert(xhr.responseText);
            }
        }
    };

    xhr.send("action=delete_user&user_id=" + encodeURIComponent(row.getAttribute("data-user-id")));
}
