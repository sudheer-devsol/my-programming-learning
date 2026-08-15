<?php

include "../config/database.php";

date_default_timezone_set("Asia/Karachi");

//================Update Comment Permission======================

if(isset($_POST["action"]) && $_POST["action"] == "update_comments"){

    $post_id = htmlspecialchars($_POST["post_id"]);
    $is_comment_allowed = htmlspecialchars($_POST["is_comment_allowed"]);

    $query = "UPDATE post SET is_comment_allowed = ? WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $is_comment_allowed, $post_id);

    if(mysqli_stmt_execute($stmt)){

        echo "success";
    }
    else{
        
        echo "Unable to update comment permission.";
    }
    mysqli_stmt_close($stmt);
}


//================Update Post Status==========================

else if(isset($_POST["action"]) && $_POST["action"] == "update_status"){

    $post_id = htmlspecialchars($_POST["post_id"]);
    $status_action = htmlspecialchars($_POST["status_action"]);

    if($status_action == "activate"){
        $status = "Active";
    }
    else{
        $status = "InActive";
    }

    $query = "UPDATE post SET post_status = ? WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $post_id);

    if(mysqli_stmt_execute($stmt)){

        echo "success";
    }
    else{
        
        echo "Unable to update status.";
    }
    mysqli_stmt_close($stmt);
}


//===================Delete Post================

else if(isset($_POST["action"]) && $_POST["action"] == "delete_post"){

    $post_id = htmlspecialchars($_POST["post_id"]);

    $query = "SELECT featured_image FROM post WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        if($row["featured_image"] != ""){

            if(file_exists("../assets/images/posts/".$row["featured_image"])){

                unlink("../assets/images/posts/".$row["featured_image"]);
            }
        }
    }
    mysqli_stmt_close($stmt);


    $query = "SELECT post_attachment_path FROM post_atachment WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while($row = mysqli_fetch_assoc($result)){

        if($row["post_attachment_path"] != ""){

            if(file_exists("../assets/images/posts/".$row["post_attachment_path"])){

                unlink("../assets/images/posts/".$row["post_attachment_path"]);
            }
        }
    }
    mysqli_stmt_close($stmt);

    
    //==============Delete Post============================
    
    $query = "DELETE FROM post WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);

    if(mysqli_stmt_execute($stmt)){

        echo "success";
    }
    else{

        echo "Unable to delete post.";
    }
    mysqli_stmt_close($stmt);


}


//===============Add Post==========================


else if(isset($_POST["action"]) && $_POST["action"] == "add_post"){

    $blog_id = htmlspecialchars($_POST["blog_id"]);

    $post_title = htmlspecialchars($_POST["post_title"]);

    $post_summary = htmlspecialchars($_POST["post_summary"]);

    $post_description = htmlspecialchars($_POST["post_description"]);

    $post_status = htmlspecialchars($_POST["post_status"]);

    $is_comment_allowed = htmlspecialchars($_POST["is_comment_allowed"]);

    $category_ids = isset($_POST["category_ids"]) ? $_POST["category_ids"] : array();

    // Required Validation==========================================

    if(empty($blog_id) || empty($post_title) || empty($post_summary) || empty($post_description)){

        header("Location: ../admin/post-edit.php?mesg=Please fill all required fields.");
        exit;
    }

    if(!isset($_FILES["featured_image"]) || $_FILES["featured_image"]["error"] != 0){

        header("Location: ../admin/post-edit.php?mesg=Please select a featured image.");
        exit;
    }

    $upload_folder = "../assets/images/posts/";

    $extension = pathinfo($_FILES["featured_image"]["name"], PATHINFO_EXTENSION);

    $image_name =strtolower( str_replace(" ","_",$post_title));

    $featured_image_name =
    $image_name . "_featured." . $extension;
   

    if(!move_uploaded_file($_FILES["featured_image"]["tmp_name"], $upload_folder . $featured_image_name)){
        
        header("Location: ../admin/post-edit.php?mesg=Unable to upload featured image.");
        exit;
    }

    // ===============Add Post===========================
    
    $query = "INSERT INTO post(blog_id, post_title, post_summary,post_description, featured_image, post_status, is_comment_allowed, created_at)
    VALUES(?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issssss", $blog_id, $post_title, $post_summary, $post_description, $featured_image_name, $post_status, $is_comment_allowed);

    if(!mysqli_stmt_execute($stmt)){

        unlink($upload_folder . $featured_image_name);
        header("Location: ../admin/post-edit.php?mesg=Unable to save post.");
        exit;
    }


    //=============Get New Post ID=============
    
    $post_id = mysqli_insert_id($conn);

    
    if(!empty($category_ids)){

        $query = "INSERT INTO post_category( post_id, category_id)
        VALUES(?,?)";

        $stmt = mysqli_prepare($conn, $query);

        foreach($category_ids as $category_id){
            
            $cat_id = htmlspecialchars($category_id);
            mysqli_stmt_bind_param($stmt, "ii", $post_id, $cat_id);
            mysqli_stmt_execute($stmt);
        }
    }

    // =================== Upload Gallery Images=======================
    if(isset($_FILES["gallery_images"])){

        $total_images = count($_FILES["gallery_images"]["name"]);

        $query = "INSERT INTO post_atachment(post_id, post_attachment_title, post_attachment_path, is_active,  created_at)
        VALUES
        (?,?,?,'Active', NOW())";

        $stmt = mysqli_prepare($conn, $query);

        for($i = 0; $i < $total_images; $i++){

            if($_FILES["gallery_images"]["error"][$i] == 0){

                $extension = pathinfo($_FILES["gallery_images"]["name"][$i], PATHINFO_EXTENSION);

                $gallery_image_name = $image_name . "_post_" . ($i + 1) . "." . $extension;

                move_uploaded_file( $_FILES["gallery_images"]["tmp_name"][$i], $upload_folder . $gallery_image_name);
                mysqli_stmt_bind_param($stmt, "iss", $post_id, $post_title, $gallery_image_name);
                mysqli_stmt_execute($stmt);
            }
        }
    }

    header("Location: ../admin/posts.php?mesg=Post published successfully.");
    exit;
}


// =================Update Post=========================

else if(isset($_POST["action"]) && $_POST["action"] == "update_post"){

    $post_id = htmlspecialchars($_POST["post_id"]);
    $blog_id = htmlspecialchars($_POST["blog_id"]);
    $post_title = htmlspecialchars($_POST["post_title"]);
    $post_summary = htmlspecialchars($_POST["post_summary"]);
    $post_description = htmlspecialchars($_POST["post_description"]);
    $post_status = htmlspecialchars($_POST["post_status"]);
    $is_comment_allowed = htmlspecialchars($_POST["is_comment_allowed"]);
    $category_ids = isset($_POST["category_ids"]) ? $_POST["category_ids"] : array();

    $upload_folder = "../assets/images/posts/";

    $image_name = strtolower(str_replace(" ","_",$post_title));

    $query = "SELECT featured_image FROM post WHERE post_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);

    $featured_image = $row["featured_image"];


    // ====================Upload New Featured Image======================

    if(isset($_FILES["featured_image"]) && $_FILES["featured_image"]["error"] == 0){

        if($featured_image != ""){

            if(file_exists($upload_folder.$featured_image)){

                unlink($upload_folder.$featured_image);
            }
        }

        $extension = pathinfo($_FILES["featured_image"]["name"], PATHINFO_EXTENSION);
        $featured_image = $image_name . "_featured." .$extension;
        move_uploaded_file($_FILES["featured_image"]["tmp_name"], $upload_folder.$featured_image);
    }


    // ===============Update Post===========================

    $query = "UPDATE post SET blog_id = ?, post_title = ?, post_summary = ?, post_description = ?, featured_image = ?, post_status = ?, is_comment_allowed = ?, updated_at = NOW()
    WHERE post_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issssssi", $blog_id, $post_title, $post_summary, $post_description, $featured_image, $post_status, $is_comment_allowed, $post_id);

    if(!mysqli_stmt_execute($stmt)){

        header("Location: ../admin/post-edit.php?id=" . $post_id . "&mesg=Unable to update post.");
        exit;
    }


    // ===============Delete Old Categories===========================

    $query = "DELETE FROM post_category WHERE post_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);

    //===================Insert New Categories=======================
    
    if(!empty($category_ids)){

        $query = "INSERT INTO post_category(post_id, category_id)
        VALUES( ?, ?)";

        $stmt = mysqli_prepare($conn, $query);

        foreach($category_ids as $category_id){

            $cat_id = htmlspecialchars($category_id);
            mysqli_stmt_bind_param($stmt, "ii", $post_id, $cat_id);
            mysqli_stmt_execute($stmt);
        }
    }

    // ================= Delete Old Gallery Images=========================
   
    if(isset($_FILES["gallery_images"]) && count($_FILES["gallery_images"]["name"]) > 0 && $_FILES["gallery_images"]["error"][0] == 0){
       
        $query = "SELECT * FROM post_atachment WHERE post_id = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $post_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while($row = mysqli_fetch_assoc($result)){

            if($row["post_attachment_path"] != ""){

                if(file_exists($upload_folder.$row["post_attachment_path"])){

                    unlink($upload_folder.$row["post_attachment_path"]);
                }
            }
        }

        //======================Delete Old Records====================
        
        $query = "DELETE FROM post_atachment WHERE post_id = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $post_id);
        mysqli_stmt_execute($stmt);

    
       //===========Upload New Gallery Images===============
        
        $total_images = count($_FILES["gallery_images"]["name"]);

        $query = "INSERT INTO post_atachment(post_id, post_attachment_title, post_attachment_path, is_active, created_at)
        VALUES (?, ?, ?, 'Active', NOW())";

        $stmt = mysqli_prepare($conn, $query);

        for($i = 0; $i < $total_images; $i++){

            if($_FILES["gallery_images"]["error"][$i] == 0){

                $extension = pathinfo($_FILES["gallery_images"]["name"][$i], PATHINFO_EXTENSION);
                $gallery_image_name = $image_name . "_post_" . ($i + 1) . "." . $extension;
                move_uploaded_file($_FILES["gallery_images"]["tmp_name"][$i], $upload_folder.$gallery_image_name);

                mysqli_stmt_bind_param($stmt, "iss", $post_id, $post_title, $gallery_image_name);
                mysqli_stmt_execute($stmt);
            }
        }
    }

    header("Location: ../admin/posts.php?mesg=Post updated successfully.");
    exit;
}

else{
    
    echo "Invalid Request.";
}