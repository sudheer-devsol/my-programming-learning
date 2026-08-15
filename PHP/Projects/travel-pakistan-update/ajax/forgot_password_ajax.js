/*
==========================================
Forgot Password Page - Validation + AJAX
==========================================
*/

function createXHR(){
    var xhr = null;

    if(window.XMLHttpRequest){
        xhr = new XMLHttpRequest();
    }else{
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    return xhr;
}

function submitForgotPassword(){
    var form = document.getElementById("forgotForm");
    var email = document.getElementById("forgotEmail");
    var submitBtn = document.getElementById("forgotSubmitBtn");

    /*
    ==========================================
    Client Side Validation
    ==========================================
    */

    if(!isValidEmail(email.value.trim())){
        email.classList.add("is-invalid-tp");
        document.getElementById("err-forgotEmail").style.display = "block";
        return false;
    }

    email.classList.remove("is-invalid-tp");
    document.getElementById("err-forgotEmail").style.display = "none";

    /*
    ==========================================
    AJAX
    ==========================================
    */

    var xhr = createXHR();

    xhr.open("POST", "process/forgot_password_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    submitBtn.disabled = true;
    submitBtn.innerHTML = "Sending...";

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4){
            submitBtn.disabled = false;
            submitBtn.innerHTML = "Send Login Details";

            if(xhr.status == 200){
                if(xhr.responseText == "success"){
                    showAlert("success", "Your login details have been sent to your email.");
                    form.reset();
                }else{
                    showAlert("error", xhr.responseText);
                }
            }else{
                showAlert("error", "Something went wrong. Please try again.");
            }
        }
    };

    xhr.send("forgot_password=1&email=" + encodeURIComponent(email.value.trim()));

    return false;
}

/*
==========================================
Validate Email
==========================================
*/
function isValidEmail(value){
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return emailRegex.test(value);
}

/*
==========================================
Alert Box
==========================================
*/
function showAlert(type, message){
    var alertBox = document.getElementById("forgotAlert");

    alertBox.className = "form-alert " + type;
    alertBox.innerHTML = message;
    alertBox.style.display = "block";
}
