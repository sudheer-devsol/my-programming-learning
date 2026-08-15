/*
==========================================
Provinces Page - Search Filter + Follow (AJAX)
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
Search Filter
==========================================
*/
function filterProvinces()
{
    var searchInput = document.getElementById("provinceSearch");
    var grid = document.getElementById("provinceGrid");
    var cards = grid.querySelectorAll(".col-lg-4");
    var noResultsMsg = document.getElementById("noResultsMsg");

    var term = searchInput.value.toLowerCase();
    var visibleCount = 0;

    for(var i = 0; i < cards.length; i++)
    {
        var card = cards[i];
        var name = card.querySelector("h3").textContent.toLowerCase();

        if(name.indexOf(term) != -1)
        {
            card.style.display = "";
            visibleCount++;
        }
        else
        {
            card.style.display = "none";
        }
    }

    if(visibleCount == 0)
    {
        noResultsMsg.style.display = "block";
    }
    else
    {
        noResultsMsg.style.display = "none";
    }
}

/*
==========================================
Follow / Unfollow Blog (AJAX)
Uses the same working endpoint as blog-details.php
and my-follows.php: process/toggle_follow.php
==========================================
*/
function followBlog(btn)
{
    var blogId = btn.getAttribute("data-blog-id");

    var xhr = createXHR();

    xhr.open("POST", "process/toggle_follow.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            var response = xhr.responseText;

            if(response == "followed")
            {
                btn.className = "btn btn-sm btn-outline-teal btn-follow";
                btn.innerHTML = "<i class=\"bi bi-check-lg\"></i> Following";
            }
            else if(response == "unfollowed")
            {
                btn.className = "btn btn-sm btn-ghost btn-follow";
                btn.innerHTML = "<i class=\"bi bi-plus-lg\"></i> Follow";
            }
            else if(response == "login_required")
            {
                window.location.href = "login.php";
            }
            else
            {
                alert(response);
            }
        }
    };

    xhr.send("blog_id=" + encodeURIComponent(blogId));
}
