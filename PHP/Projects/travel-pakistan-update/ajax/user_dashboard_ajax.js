/*
==========================================
User Dashboard AJAX
==========================================
*/

function handleLogout()
{
    var xhr = createXHR();

    xhr.open("POST", "../process/logout_process.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            window.location.href = "../login.php";
        }
    };

    xhr.send("action=logout");

    return false;
}

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
