<?php
    $page_title = "";
    $active_page = "posts";

    include "includes/header.php";
    include "config/database.php";
    include "process/post_details_process.php";

    $page_title = $post["post_title"];
?>

<article class="section-tight" style="padding-top:48px;">
    <div class="container">
        <nav style="font-family:var(--font-label);text-transform:uppercase;letter-spacing:.05em;font-size:.8rem;color:var(--ink-soft);" class="mb-3">
            <a href="index.php">Home</a> / 
            <a href="blog-details.php?blog_id=<?=$post["blog_id"];?>">
                <?=htmlspecialchars($post["blog_title"]);?>
            </a> / 
            <span><?=htmlspecialchars($post["post_title"]);?></span>
        </nav>

        <div class="row g-5">
            <!-- ========================= Left Side Section ================================ -->
            <div class="col-lg-8">
                <!-- Categories -->
                <div class="cat-badges mb-3">
                    <?php while($category = mysqli_fetch_assoc($category_result)){ ?>
                        <span class="badge-stamp">
                            <?=htmlspecialchars($category["category_title"]);?>
                        </span>
                    <?php } ?>
                </div>

                <!-- Post Title -->
                <h1 style="font-size:2.3rem;"><?=htmlspecialchars($post["post_title"]);?></h1>

                <!-- Meta Data -->
                <div class="post-meta mb-4">
                    <span> 
                        <i class="bi bi-person-circle"></i> 
                        <?=htmlspecialchars($post["first_name"]." ".$post["last_name"]);?> 
                    </span>
                    <span class="dot"> 
                        <i class="bi bi-calendar3"></i>
                        <?=date("F d, Y", strtotime($post["created_at"]));?>
                    </span>
                    <span class="dot">
                        <i class="bi bi-signpost-split"></i>
                        <?=htmlspecialchars($post["blog_title"]);?>
                    </span>
                </div>

                <!-- Featured Image -->
                <div class="img-wrap mb-4" style="border-radius:var(--radius-md);overflow:hidden;">
                    <img src="<?= !empty($post["featured_image"]) ? "assets/images/posts/".$post["featured_image"] : "assets/images/posts/default.jpg";?>"
                         alt="<?=htmlspecialchars($post["post_title"]);?>">
                </div>

                <!-- Post Body Description -->
                <div class="post-body">
                    <?=nl2br($post["post_description"]);?>
                </div>

                <!-- Gallery Lightbox Section -->
                <?php if(mysqli_num_rows($gallery_result) > 0){ ?>
                    <div class="mt-5">
                        <h3 class="mb-3">Gallery</h3>
                        <div class="row g-2" id="galleryThumbs">
                            <?php 
                            $index = 0;
                            while($gallery = mysqli_fetch_assoc($gallery_result)){ 
                            ?>
                                <div class="col-lg-3 col-md-4 col-6">
                                    <div class="gallery-thumb" data-index="<?=$index;?>" onclick="openLightbox(this);">
                                        <img src="assets/images/posts/<?=$gallery["post_attachment_path"];?>"
                                             alt="<?=htmlspecialchars($gallery["post_attachment_title"]);?>">
                                    </div>
                                </div>
                            <?php 
                                $index++;
                            } 
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <!-- ============== Comments Section =============================-->
                <div class="mt-5" id="commentsSection" data-post-id="<?=$post['post_id'];?>">
                    <h3 class="mb-3">
                        <i class="bi bi-chat-left-text"></i> Reviews 
                        <span style="color:var(--ink-soft);font-weight:400;">(<?=$comments_count;?>)</span>
                    </h3>

                    <div id="commentsContainer">
                        <?php if($comments_count > 0){ ?>
                            <?php while($comment = mysqli_fetch_assoc($comments_result)){ ?>
                                <div class="comment-item mb-3 d-flex gap-3">
                                    <img src="<?= !empty($comment["user_image"]) ? "assets/images/users/".$comment["user_image"] : "https://i.pravatar.cc/80?img=12"; ?>" 
                                        alt="User Avatar" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                                    <div>
                                        <div class="c-name" style="font-weight:600;"><?=htmlspecialchars($comment["first_name"]." ".$comment["last_name"]);?></div>
                                        <div class="c-date" style="font-size:0.8rem;color:var(--ink-soft);"><?=date("F d, Y", strtotime($comment["created_at"]));?></div>
                                        <p class="mt-2 mb-0"><?=nl2br(htmlspecialchars($comment["comment"]));?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <p id="noCommentsText" class="text-muted">No reviews yet. Be the first to share your experience!</p>
                        <?php } ?>
                    </div>

                    <!-- New comment form handling -->
                    <?php if($post["is_comment_allowed"] == 1){ ?>
                        <?php if(isset($_SESSION['user']) || isset($_SESSION['user_id'])){ ?>
                            <!-- User is Logged In: Show Comment Box -->
                            <div class="mt-4 form-tp">
                                <label class="form-label font-weight-bold">Leave a review</label>
                                <div id="commentAlert" class="form-alert mb-2" style="display:none;"></div>
                                <textarea class="form-control mb-2" id="commentText" rows="3" placeholder="Share your experience..."></textarea>
                                <button class="btn btn-teal btn-sm" id="submitComment" onclick="submitPostComment();">Post Review</button>
                                <div class="field-error text-danger mt-1" id="commentError" style="display:none;">Please write a review before submitting.</div>
                            </div>
                        <?php } else { ?>
                            <!-- User is NOT Logged In: Disable Box & Show Login Link -->
                            <div class="mt-4 p-3 style-box text-center" style="background-color: var(--surface-subtle, #f8f9fa); border-radius: var(--radius-md, 8px); border: 1px solid #e9ecef;">
                                <p class="mb-0 text-muted" style="font-size: 0.95rem;">
                                    <i class="bi bi-lock-fill me-1"></i> Please <a href="login.php" style="color:var(--teal); font-weight:600; text-decoration:underline;">Log In</a> or <a href="register.php" style="color:var(--teal); font-weight:600; text-decoration:underline;">Register</a> to make a comment on this post.
                                </p>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="mt-4 text-muted"><em>Comments are disabled for this post.</em></p>
                    <?php } ?>
                </div>
            </div>

            <!-- ====================== Right Sidebar =================================== -->
            <div class="col-lg-4">
                <div class="panel-tp" style="position:sticky;top:96px;">
                    <div class="panel-head">
                        <strong> Related Posts </strong>
                    </div>
                    <div class="panel-body d-flex flex-column gap-3">
                        <?php while($related = mysqli_fetch_assoc($related_post_result)){ ?>
                            <a href="post-details.php?post_id=<?=$related["post_id"];?>" class="d-flex gap-3 text-decoration-none">
                                <img src="<?= !empty($related["featured_image"]) ? "assets/images/posts/".$related["featured_image"] : "assets/images/posts/default.jpg"; ?>"
                                     style="width:96px;height:72px;object-fit:cover;border-radius:var(--radius-sm);flex-shrink:0;">
                                <div>
                                    <h4 style="font-size:.95rem;margin-bottom:4px;"><?=htmlspecialchars($related["post_title"]);?></h4>
                                    <div class="post-meta" style="font-size:.78rem;">
                                        <span><?=date("M d, Y", strtotime($related["created_at"]));?></span>
                                    </div>
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Lightbox Modal -->
<div class="lightbox-tp" id="lightbox" onclick="lightboxBackdropClick(event);">
    <button class="lb-btn lb-close" id="lbClose" onclick="closeLightbox();"><i class="bi bi-x-lg"></i></button>
    <img src="" alt="" id="lbImage">
    <div class="lb-controls">
        <button class="lb-btn" id="lbPrev" onclick="showPrev();"><i class="bi bi-chevron-left"></i></button>
        <span style="color:#fff;font-family:var(--font-label);" id="lbCounter">1 / 1</span>
        <button class="lb-btn" id="lbNext" onclick="showNext();"><i class="bi bi-chevron-right"></i></button>
    </div>
</div>

<?php include "includes/footer.php"; ?>
<script src="ajax/post_details_ajax.js"></script>
</body>
</html>