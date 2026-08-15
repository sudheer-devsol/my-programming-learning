/*
==========================================
Admin Post Edit Page - Validation + Image Preview
==========================================
No AJAX is used here. The form submits normally
(method="POST" enctype="multipart/form-data") and
PHP handles everything in posts_process.php.
==========================================
*/

function validatePostForm()
{
    var title = document.getElementById("postTitle");
    var summary = document.getElementById("postSummary");
    var description = document.getElementById("postDescription");
    var galleryInput = document.getElementById("postGalleryImages");
    var alertBox = document.getElementById("postFormAlert");

    var valid = true;

    valid = validateField(title, "err-postTitle", title.value.trim() != "") && valid;
    valid = validateField(summary, "err-postSummary", summary.value.trim() != "") && valid;
    valid = validateField(description, "err-postDescription", description.value.trim() != "") && valid;

    if(!valid)
    {
        return false;
    }

    if(galleryInput.files.length > 4)
    {
        if(alertBox)
        {
            alertBox.className = "form-alert error";
            alertBox.innerHTML = "Maximum 4 gallery images are allowed.";
            alertBox.style.display = "block";
        }
        return false;
    }

    return true;
}

function validateField(field, errorId, condition)
{
    var error = document.getElementById(errorId);

    if(!condition)
    {
        field.classList.add("is-invalid-tp");
        error.style.display = "block";
        return false;
    }

    field.classList.remove("is-invalid-tp");
    error.style.display = "none";

    return true;
}

/*
==========================================
Featured Image Preview
==========================================
*/
function previewFeaturedImage(input)
{
    var preview = document.getElementById("featuredPreview");

    if(input.files && input.files[0])
    {
        var reader = new FileReader();

        reader.onload = function(e)
        {
            preview.src = e.target.result;
            preview.style.display = "block";
        };

        reader.readAsDataURL(input.files[0]);
    }
}

/*
==========================================
Gallery Images Preview
==========================================
*/
function previewGalleryImages(input)
{
    var preview = document.getElementById("galleryPreview");

    if(!preview)
    {
        return;
    }

    preview.innerHTML = "";

    for(var i = 0; i < input.files.length; i++)
    {
        var reader = new FileReader();

        reader.onload = (function()
        {
            return function(e)
            {
                var col = document.createElement("div");
                col.className = "col-3";

                var img = document.createElement("img");
                img.src = e.target.result;
                img.className = "img-fluid rounded";

                col.appendChild(img);
                preview.appendChild(col);
            };
        })();

        reader.readAsDataURL(input.files[i]);
    }
}
