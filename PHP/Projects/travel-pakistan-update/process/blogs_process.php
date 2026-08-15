<?php
include "../includes/admin-session.php";
include "../config/database.php";

if(isset($_POST["action"])){

    $action = $_POST["action"];

//===================ADD BLOG===================================

    if($action == "add_blog"){

        $blog_title = trim($_POST["blog_title"]);
        $post_per_page = $_POST["post_per_page"];
        $blog_status = $_POST["blog_status"];

        $user_id = 1;

        if($blog_title == ""){
            header("Location: ../admin/blogs.php?mesg=Blog title is required.");
            exit();
        }

        $check_query = "SELECT * FROM blog WHERE blog_title = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $blog_title);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if(mysqli_num_rows($check_result) > 0){
            header("Location: ../admin/blogs.php?mesg=Blog already exists.");
            exit();
        }

        $imageName = "default-blog.jpg";

        if(isset($_FILES["blog_background_image"]) && $_FILES["blog_background_image"]["name"] != ""){

            $extension = pathinfo($_FILES["blog_background_image"]["name"], PATHINFO_EXTENSION);
            $imageName = time() . rand(1000, 9999) . "." . $extension;
            move_uploaded_file($_FILES["blog_background_image"]["tmp_name"], "../assets/images/blogs/" . $imageName);
        }

        $query = "INSERT INTO blog(user_id, blog_title, post_per_page, blog_background_image, blog_status, updated_at)
        VALUES(?, ?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "isiss", $user_id, $blog_title, $post_per_page, $imageName, $blog_status);

        if(mysqli_stmt_execute($stmt)){

            header("Location: ../admin/blogs.php?mesg=Blog added successfully.");
        }else{
            header("Location: ../admin/blogs.php?mesg=Unable to add blog.");
        }
        exit();
    }

//==================== GET BLOG================================

    if($action == "get_blog"){

        $blog_id = $_POST["blog_id"];

        $query = "SELECT * FROM blog WHERE blog_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $blog_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $blog = mysqli_fetch_assoc($result);

        // Return simple pipe-separated plain text instead of JSON
        echo $blog["blog_id"] . "|" . $blog["blog_title"] . "|" . $blog["post_per_page"] . "|" . $blog["blog_status"];

        exit();
    }

    //======================== UPDATE BLOG==============================

    if($action == "update_blog"){

        $blog_id = $_POST["blog_id"];
        $blog_title = trim($_POST["blog_title"]);
        $post_per_page = $_POST["post_per_page"];
        $blog_status = $_POST["blog_status"];

        if($blog_title == ""){
            header("Location: ../admin/blogs.php?mesg=Blog title is required.");
            exit();
        }

        $new_image = "";

        if(isset($_FILES["blog_background_image"]) && $_FILES["blog_background_image"]["name"] != ""){

            $extension = pathinfo($_FILES["blog_background_image"]["name"], PATHINFO_EXTENSION);
            $new_image = time() . rand(1000, 9999) . "." . $extension;
            move_uploaded_file($_FILES["blog_background_image"]["tmp_name"], "../assets/images/blogs/" . $new_image);
        }

        if($new_image != ""){

            $query = "UPDATE blog SET blog_title = ?, post_per_page = ?, blog_status = ?, blog_background_image = ?, updated_at = NOW() WHERE blog_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sissi", $blog_title, $post_per_page, $blog_status, $new_image, $blog_id);

        }else{

            $query = "UPDATE blog SET blog_title = ?, post_per_page = ?, blog_status = ?, updated_at = NOW() WHERE blog_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sisi", $blog_title, $post_per_page, $blog_status, $blog_id);
        }

        if(mysqli_stmt_execute($stmt)){
            header("Location: ../admin/blogs.php?mesg=Blog updated successfully.");
        }else{
            header("Location: ../admin/blogs.php?mesg=Unable to update blog.");
        }
        exit();
    }


    //=====================ACTIVATE / DEACTIVATE=================================

    if($action == "update_status"){

        $blog_id = $_POST["blog_id"];
        $status_action = $_POST["status_action"];

        if($status_action == "activate"){

            $status = "Active";
        }
        else{

            $status = "InActive";
        }

        $query = "UPDATE blog SET blog_status = ? WHERE blog_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $status, $blog_id);

        if(mysqli_stmt_execute($stmt)){

            echo "success";
        }else{

            echo "Unable to update blog status.";
        }
        exit();
    }


//===================DELETE BLOG===================================

if($action == "delete_blog"){

    $blog_id = $_POST["blog_id"];

    $query = "SELECT blog_background_image FROM blog WHERE blog_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $blog_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){

        $image = $row["blog_background_image"];
        if(!empty($image) && $image != "default-blog.jpg"){

            $imagePath = "../assets/images/blogs/" . $image;
            if(file_exists($imagePath)){
                unlink($imagePath);
            }
        }
    }

    $delete_query = "DELETE FROM blog WHERE blog_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $blog_id);

    if(mysqli_stmt_execute($delete_stmt)){

        echo "success";
    }
    else{

        echo "Unable to delete blog.";
    }
    exit();
    }
}
?>
