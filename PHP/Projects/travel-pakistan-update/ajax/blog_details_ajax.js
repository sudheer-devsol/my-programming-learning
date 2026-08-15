/*
==========================================
Blog Details Page - Category Filter + Follow (AJAX)
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

function filterPosts(){
    var categoryFilter = document.getElementById("categoryFilter");
    var postCards = document.querySelectorAll("#postGrid > div");

    if(!categoryFilter){
        return;
    }

    var value = categoryFilter.value;

    for(var i = 0; i < postCards.length; i++){
        var col = postCards[i];
        var card = col.querySelector("[data-cat]");

        if(card){
            var cat = card.getAttribute("data-cat");

            if(value == "all" || cat == value){
                col.style.display = "";
            }else{
                col.style.display = "none";
            }
        }
    }
}

function followBlog(btn){
    var blogId = btn.getAttribute("data-blog-id");

    var xhr = createXHR();

    xhr.open("POST", window.location.href, true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            var res = xhr.responseText;
            var followersElem = document.getElementById("followersCount");
            var currentFollowers = followersElem ? (parseInt(followersElem.innerText) || 0) : 0;

            if(res == "followed"){
                btn.className = "btn btn-danger btn-follow";
                btn.innerHTML = '<i class="bi bi-dash-lg"></i> Unfollow';
                if(followersElem){
                    followersElem.innerText = currentFollowers + 1;
                }
            }else if(res == "unfollowed"){
                btn.className = "btn btn-marigold btn-follow";
                btn.innerHTML = '<i class="bi bi-plus-lg"></i> Follow';
                if(followersElem){
                    followersElem.innerText = Math.max(0, currentFollowers - 1);
                }
            }else if(res == "login_required"){
                window.location.href = "login.php";
            }else{
                alert(res);
            }
        }
    };

    xhr.send("blog_id=" + encodeURIComponent(blogId) + "&action=follow_blog");
}
