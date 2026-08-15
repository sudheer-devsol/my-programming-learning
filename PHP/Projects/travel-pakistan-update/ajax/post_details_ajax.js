/*
==========================================
Post Details Page - Gallery Lightbox + Comment Submit (AJAX)
==========================================
*/

var lb_images = [];
var lb_currentIndex = 0;
var lb_slideTimer = null;

function createXHR() {
    var xhr = null;

    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    } else {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    return xhr;
}

/*
==========================================
Gallery / Lightbox Slider
==========================================
*/
function openLightbox(thumb) {
    var thumbs = document.querySelectorAll(".gallery-thumb");
    var lightbox = document.getElementById("lightbox");

    if (!lightbox) {
        return;
    }

    lb_images = [];
    for (var i = 0; i < thumbs.length; i++) {
        var imgEl = thumbs[i].querySelector("img");
        if (imgEl) {
            lb_images.push(imgEl.src);
        }
    }

    lb_currentIndex = parseInt(thumb.getAttribute("data-index"), 10) || 0;

    lightbox.classList.add("open");
    renderLightboxImage();
    startAutoSlide();
}

function closeLightbox() {
    var lightbox = document.getElementById("lightbox");

    if (lightbox) {
        lightbox.classList.remove("open");
    }

    stopAutoSlide();
}

function lightboxBackdropClick(e) {
    var lightbox = document.getElementById("lightbox");

    if (e.target == lightbox) {
        closeLightbox();
    }
}

function renderLightboxImage() {
    var lbImage = document.getElementById("lbImage");
    var lbCounter = document.getElementById("lbCounter");

    if (lbImage) {
        lbImage.src = lb_images[lb_currentIndex];
    }

    if (lbCounter) {
        lbCounter.textContent = (lb_currentIndex + 1) + " / " + lb_images.length;
    }
}

function showPrev() {
    lb_currentIndex = (lb_currentIndex - 1 + lb_images.length) % lb_images.length;
    renderLightboxImage();
    restartAutoSlide();
}

function showNext() {
    lb_currentIndex = (lb_currentIndex + 1) % lb_images.length;
    renderLightboxImage();
    restartAutoSlide();
}

function startAutoSlide() {
    stopAutoSlide();
    lb_slideTimer = setInterval(showNext, 3000);
}

function stopAutoSlide() {
    if (lb_slideTimer) {
        clearInterval(lb_slideTimer);
        lb_slideTimer = null;
    }
}

function restartAutoSlide() {
    stopAutoSlide();
    startAutoSlide();
}

// Keyboard navigation support (Esc, Left Arrow, Right Arrow)
document.onkeydown = function (e) {
    var lightbox = document.getElementById("lightbox");

    if (!lightbox || !lightbox.classList.contains("open")) {
        return;
    }

    if (e.key == "Escape") {
        closeLightbox();
    }
    if (e.key == "ArrowLeft") {
        showPrev();
    }
    if (e.key == "ArrowRight") {
        showNext();
    }
};

/*
==========================================
Comment Submit (AJAX)
==========================================
*/
function submitPostComment() {
    var commentsSection = document.getElementById("commentsSection");
    var commentText = document.getElementById("commentText");
    var commentError = document.getElementById("commentError");
    var commentAlert = document.getElementById("commentAlert");

    if (!commentsSection || !commentText) {
        return;
    }

    var postId = commentsSection.getAttribute("data-post-id");

    if (commentError) {
        commentError.style.display = "none";
    }
    if (commentAlert) {
        commentAlert.style.display = "none";
    }

    var commentValue = commentText.value.trim();

    if (commentValue == "") {
        if (commentError) {
            commentError.style.display = "block";
        }
        return;
    }

    var xhr = createXHR();

    xhr.open("POST", "process/add-comment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var response = xhr.responseText;

                if (response == "success") {
                    if (commentAlert) {
                        commentAlert.className = "form-alert success text-success mb-2";
                        commentAlert.textContent = "Review posted successfully! Reloading...";
                        commentAlert.style.display = "block";
                    }
                    commentText.value = "";

                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else if (response == "login_required") {
                    window.location.href = "login.php";
                } else {
                    if (commentAlert) {
                        commentAlert.className = "form-alert error text-danger mb-2";
                        commentAlert.textContent = response;
                        commentAlert.style.display = "block";
                    }
                }
            } else {
                if (commentAlert) {
                    commentAlert.className = "form-alert error text-danger mb-2";
                    commentAlert.textContent = "Server error occurred. Please try again.";
                    commentAlert.style.display = "block";
                }
            }
        }
    };

    xhr.send("post_id=" + encodeURIComponent(postId) + "&comment=" + encodeURIComponent(commentValue));
}
