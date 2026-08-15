/*
==========================================
Admin Feedback Page - Search + Delete (AJAX)
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
Search Feedback
==========================================
*/
function filterFeedback()
{
    var searchInput = document.getElementById("feedbackSearch");
    var rows = document.querySelectorAll("#feedbackTable tbody tr");
    var term = searchInput.value.toLowerCase();

    for(var i = 0; i < rows.length; i++)
    {
        var row = rows[i];
        var name = row.children[0].textContent.toLowerCase();
        var email = row.children[1].textContent.toLowerCase();

        if(name.indexOf(term) != -1 || email.indexOf(term) != -1)
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
Delete Feedback
==========================================
*/
function deleteFeedback(btn)
{
    if(!confirm("Delete this feedback?"))
    {
        return;
    }

    var row = btn.closest("tr");

    var feedbackId = row.getAttribute("data-feedback-id");

    var xhr = createXHR();

    xhr.open("POST", "../process/feedback_process.php", true);

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

    xhr.send("action=delete_feedback&feedback_id=" + encodeURIComponent(feedbackId));
}
