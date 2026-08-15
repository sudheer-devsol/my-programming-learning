/*
========================================================
Admin Blogs Page - Filter + CRUD
========================================================
Note: Add/Edit Blog now submits as a normal HTML form
(method="POST" enctype="multipart/form-data") so image
uploads do not use AJAX or FormData. This file only
handles filtering, loading data into the modal, and the
simple status/delete actions.
========================================================
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

/*
==========================================
Filter
==========================================
*/
function applyBlogFilters()
{
    var statusFilter = document.getElementById("blogStatusFilter");
    var searchInput = document.getElementById("blogSearch");
    var rows = document.querySelectorAll("table tbody tr");

    var selectedStatus = statusFilter.value.toLowerCase();
    var searchTerm = searchInput.value.toLowerCase();

    for(var i = 0; i < rows.length; i++)
    {
        var row = rows[i];
        var blogName = row.children[0].textContent.toLowerCase();
        var blogStatus = row.children[2].textContent.replace(/^\s+|\s+$/g, "").toLowerCase();

        var statusMatch = (selectedStatus == "all" || blogStatus == selectedStatus);
        var searchMatch = (blogName.indexOf(searchTerm) != -1);

        if(statusMatch && searchMatch)
        {
            row.style.display = "";
        }
        else
        {
            row.style.display = "none";
        }
    }
}

/*
==========================================
Add Blog (reset modal)
==========================================
*/
function openAddBlogModal()
{
    var blogForm = document.getElementById("blogForm");
    var blogModalTitle = document.getElementById("blogModalTitle");
    var blogModalAlert = document.getElementById("blogModalAlert");

    blogModalTitle.textContent = "Add Blog";

    blogForm.reset();

    blogModalAlert.style.display = "none";

    document.getElementById("blogId").value = "";
    document.getElementById("blogFormAction").value = "add_blog";
}

/*
==========================================
Load Blog (Edit)
==========================================
*/
function loadBlog(blogId)
{
    var xhr = createXHR();

    xhr.open("POST", "../process/blogs_process.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            // Response is plain text separated by "|"
            var parts = xhr.responseText.split("|");

            document.getElementById("blogModalTitle").textContent = "Edit Blog";

            document.getElementById("blogId").value = parts[0];
            document.getElementById("blogFormAction").value = "update_blog";
            document.getElementById("blogTitle").value = parts[1];
            document.getElementById("blogPostsPerPage").value = parts[2];
            document.getElementById("blogStatus").value = parts[3];

            var modal = new bootstrap.Modal(document.getElementById("blogModal"));

            modal.show();
        }
    };

    xhr.send("action=get_blog&blog_id=" + encodeURIComponent(blogId));
}

/*
==========================================
Validate Blog Form (before normal submit)
==========================================
*/
function validateBlogForm()
{
    var blogTitle = document.getElementById("blogTitle");

    if(blogTitle.value.trim() == "")
    {
        blogTitle.classList.add("is-invalid-tp");
        return false;
    }

    blogTitle.classList.remove("is-invalid-tp");

    return true;
}

/*
==========================================
Update Blog Status
==========================================
*/
function updateBlogStatus(btn, actionType)
{
    var row = btn.closest("tr");

    var xhr = createXHR();

    xhr.open("POST", "../process/blogs_process.php", true);

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
        "&blog_id=" + encodeURIComponent(row.getAttribute("data-blog-id"));

    xhr.send(params);
}

/*
==========================================
Delete Blog
==========================================
*/
function deleteBlog(btn)
{
    if(!confirm("Delete this blog permanently?"))
    {
        return;
    }

    var row = btn.closest("tr");

    var xhr = createXHR();

    xhr.open("POST", "../process/blogs_process.php", true);

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

    var params = "action=delete_blog&blog_id=" + encodeURIComponent(row.getAttribute("data-blog-id"));

    xhr.send(params);
}
