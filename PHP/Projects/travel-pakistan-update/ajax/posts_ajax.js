/*
==========================================
Admin Posts Page - Filter + CRUD (AJAX)
==========================================
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
function applyPostFilters()
{
    var blogFilterSelect = document.getElementById("blogFilterSelect");
    var postSearch = document.getElementById("postSearch");
    var rows = document.querySelectorAll("#postsTable tbody tr");

    var selectedBlog = blogFilterSelect.value;
    var searchTerm = postSearch.value.toLowerCase();

    for(var i = 0; i < rows.length; i++)
    {
        var row = rows[i];
        var rowBlog = row.getAttribute("data-blog");
        var postTitle = row.children[0].textContent.toLowerCase();

        var blogMatch = (selectedBlog == "all" || rowBlog == selectedBlog);
        var searchMatch = (postTitle.indexOf(searchTerm) != -1);

        if(blogMatch && searchMatch)
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
Update Comment Permission
==========================================
*/
function updateComments(toggle)
{
    var row = toggle.closest("tr");

    var xhr = createXHR();

    xhr.open("POST", "../process/posts_process.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            if(xhr.responseText != "success")
            {
                toggle.checked = !toggle.checked;

                alert(xhr.responseText);
            }
        }
    };

    var params = "action=update_comments&post_id=" + encodeURIComponent(row.getAttribute("data-post-id")) +
        "&is_comment_allowed=" + (toggle.checked ? "1" : "0");

    xhr.send(params);
}

/*
==========================================
Activate / Deactivate
==========================================
*/
function updatePostStatus(btn, actionType)
{
    var row = btn.closest("tr");

    var xhr = createXHR();

    xhr.open("POST", "../process/posts_process.php", true);

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
        "&post_id=" + encodeURIComponent(row.getAttribute("data-post-id"));

    xhr.send(params);
}

/*
==========================================
Delete Post
==========================================
*/
function deletePost(btn)
{
    if(!confirm("Delete this post permanently?"))
    {
        return;
    }

    var row = btn.closest("tr");

    var xhr = createXHR();

    xhr.open("POST", "../process/posts_process.php", true);

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

    var params = "action=delete_post&post_id=" + encodeURIComponent(row.getAttribute("data-post-id"));

    xhr.send(params);
}
