/*
================Admin Categories Page Ajax==========================
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

// ==============Add Category============================

function submitCategory()
{
    var addCategoryAlert = document.getElementById("addCategoryAlert");

    var xhr = createXHR();

    xhr.open("POST", "../process/categories_process.php", true);

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
                addCategoryAlert.className = "form-alert error";
                addCategoryAlert.style.display = "block";
                addCategoryAlert.textContent = xhr.responseText;
            }
        }
    };

    var params = "category_title=" + encodeURIComponent(document.getElementById("categoryTitle").value.trim()) +
        "&category_description=" + encodeURIComponent(document.getElementById("categoryDescription").value.trim()) +
        "&category_status=" + encodeURIComponent(document.getElementById("categoryStatus").value) +
        "&add_category=1";

    xhr.send(params);
}

// ============Edit Category==============================

function loadCategory(categoryId)
{
    var xhr = createXHR();

    xhr.open("POST", "../process/categories_process.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4)
        {
            if(xhr.status == 200)
            {
                // Response is plain text separated by "|"
                var parts = xhr.responseText.split("|");

                document.getElementById("editCategoryId").value = parts[0];
                document.getElementById("editCategoryTitle").value = parts[1];
                document.getElementById("editCategoryDescription").value = parts[2];
                document.getElementById("editCategoryStatus").value = parts[3];
            }
            else
            {
                alert("Unable to load category.");
            }
        }
    };

    xhr.send("category_id=" + encodeURIComponent(categoryId) + "&get_category=1");
}

// =================Update Category=========================

function updateCategory()
{
    var editCategoryAlert = document.getElementById("editCategoryAlert");

    var xhr = createXHR();

    xhr.open("POST", "../process/categories_process.php", true);

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
                editCategoryAlert.className = "form-alert error";
                editCategoryAlert.style.display = "block";
                editCategoryAlert.textContent = xhr.responseText;
            }
        }
    };

    var params = "category_id=" + encodeURIComponent(document.getElementById("editCategoryId").value) +
        "&category_title=" + encodeURIComponent(document.getElementById("editCategoryTitle").value.trim()) +
        "&category_description=" + encodeURIComponent(document.getElementById("editCategoryDescription").value.trim()) +
        "&category_status=" + encodeURIComponent(document.getElementById("editCategoryStatus").value) +
        "&update_category=1";

    xhr.send(params);
}

// =================Activate / Deactivate Category=========================

function updateCategoryStatus(btn, status)
{
    var row = btn.closest("tr");

    var xhr = createXHR();

    xhr.open("POST", "../process/categories_process.php", true);

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

    var params = "category_id=" + encodeURIComponent(row.getAttribute("data-category-id")) +
        "&category_status=" + encodeURIComponent(status) +
        "&update_category_status=1";

    xhr.send(params);
}

// ==============  Delete Category============================

function deleteCategory(btn)
{
    if(!confirm("Delete this category permanently?"))
    {
        return;
    }

    var row = btn.closest("tr");

    var xhr = createXHR();

    xhr.open("POST", "../process/categories_process.php", true);

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

    xhr.send("category_id=" + encodeURIComponent(row.getAttribute("data-category-id")) + "&delete_category=1");
}
