/*
==========================================
User Profile Page - Validation + Image Preview
==========================================
The form fields already come from PHP on page load, and
the form now submits normally (method="POST"
enctype="multipart/form-data") so the profile photo does
not use AJAX or FormData. This file only handles client
side validation and the live image preview.
==========================================
*/

function validateProfileForm()
{
    var firstName = document.getElementById("pFirstName");
    var lastName = document.getElementById("pLastName");
    var address = document.getElementById("pAddress");

    var valid = true;

    valid = validateField(firstName, "err-pFirstName", firstName.value.trim() != "") && valid;
    valid = validateField(lastName, "err-pLastName", lastName.value.trim() != "") && valid;
    valid = validateField(address, "err-pAddress", address.value.trim() != "") && valid;

    return valid;
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
Image Preview
==========================================
*/
function previewProfileImage(input)
{
    var imagePreview = document.getElementById("profilePreview");

    var file = input.files[0];

    if(file)
    {
        var reader = new FileReader();

        reader.onload = function(e)
        {
            imagePreview.src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
}
