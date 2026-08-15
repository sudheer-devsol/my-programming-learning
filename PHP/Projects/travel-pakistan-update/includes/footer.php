<footer class="tp-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="footer-brand mb-3">Travel Pakistan</div>
                <p class="mb-4">A field guide to Pakistan, province by province — written for travelers who'd rather trust a local trail than a top-ten list.</p>
                <div class="d-flex gap-2">
                    <a href="#" class="social-dot"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-dot"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-dot"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-dot"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h4>Explore</h4>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><a href="provinces.php">Provinces</a></li>
                    <li class="mb-2"><a href="categories.php">Categories</a></li>
                    <li class="mb-2"><a href="posts.php">Latest Posts</a></li>
                    <li class="mb-2"><a href="about.php">About</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-6">
                <h4>Provinces</h4>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><a href="blog-details.php?id=1">Punjab</a></li>
                    <li class="mb-2"><a href="blog-details.php?id=2">Sindh</a></li>
                    <li class="mb-2"><a href="blog-details.php?id=3">Khyber Pakhtunkhwa</a></li>
                    <li class="mb-2"><a href="blog-details.php?id=5">Gilgit-Baltistan</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h4>Stay in the loop</h4>
                <p class="mt-3">Follow a province from your dashboard and new posts land in your feed automatically.</p>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between flex-wrap gap-2">
            <small>&copy; <?= date("Y") ?> Travel Pakistan. All rights reserved.</small>
            <small><a href="contact.php">Contact</a> &nbsp;·&nbsp; <a href="#">Privacy</a> &nbsp;·&nbsp; <a href="#">Terms</a></small>
        </div>
    </div>
</footer>

<!-- Back to top -->
<button id="backToTop" class="btn btn-teal btn-sm" style="position:fixed;bottom:24px;right:24px;border-radius:50%;width:46px;height:46px;display:none;z-index:999;" onclick="scrollToTop();">
    <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/*
==========================================
Global UI helper (back-to-top) - shared visual behavior only.
Page-specific AJAX/validation logic stays in each page's own <script> block.
==========================================
*/

window.onscroll = function()
{
    var backToTop = document.getElementById("backToTop");

    if(window.scrollY > 400)
    {
        backToTop.style.display = "flex";
    }
    else
    {
        backToTop.style.display = "none";
    }
};

function scrollToTop()
{
    window.scrollTo(0, 0);
}
</script>
