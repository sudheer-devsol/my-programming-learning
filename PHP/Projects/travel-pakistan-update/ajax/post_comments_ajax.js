/*
==========================================
Admin Comments Page - Filter + Toggle + Delete (AJAX)
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

function setCommentFilter(chip)
{
    var chips = document.querySelectorAll(".comment-filter-chip");

    for(var i = 0; i < chips.length; i++)
    {
        chips[i].classList.remove("btn-teal", "active");
        chips[i].classList.add("btn-ghost");
    }

    chip.classList.remove("btn-ghost");
    chip.classList.add("btn-teal", "active");

    applyCommentFilters();
}

function applyCommentFilters()
{
    var activeChip = document.querySelector(".comment-filter-chip.active");
    var searchInput = document.getElementById("commentSearch");
    var rows = document.querySelectorAll("#commentsTable tbody tr");

    var status = activeChip ? activeChip.getAttribute("data-status") : "all";
    var term = searchInput ? searchInput.value.toLowerCase() : "";

    for(var i = 0; i < rows.length; i++)
    {
        var row = rows[i];
        var rowStatus = row.getAttribute("data-status");
        var text = row.children[1].textContent.toLowerCase();

        var match = (status == "all" || rowStatus == status) && text.indexOf(term) != -1;

        if(match)
        {
            row.style.display = "";
        }
        else
        {
            row.style.display = "none";
        }
    }
}

function toggleComment(btn)
{
    var row = btn.closest("tr");
    var commentId = row.getAttribute("data-comment-id");
    var action = btn.classList.contains("btn-teal") ? "activate" : "deactivate";

    var xhr = createXHR();

    xhr.open("POST", "../process/post_comments_process.php", true);

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

    var params = "action=update_status&comment_id=" + encodeURIComponent(commentId) +
        "&status_action=" + encodeURIComponent(action);

    xhr.send(params);
}

function deleteComment(btn)
{
    if(!confirm("Delete this comment?"))
    {
        return;
    }

    var row = btn.closest("tr");
    var commentId = row.getAttribute("data-comment-id");

    var xhr = createXHR();

    xhr.open("POST", "../process/post_comments_process.php", true);

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

    xhr.send("action=delete_comment&comment_id=" + encodeURIComponent(commentId));
}
