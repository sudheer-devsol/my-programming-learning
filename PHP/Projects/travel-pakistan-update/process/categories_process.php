<?php

include "../config/database.php";


// ===============Add Category=====================================


if(isset($_POST["add_category"])){

    // echo "</pre>";
    // print_r($_POST);
    // echo "</pre>";
    // die();

    $category_title = trim($_POST["category_title"]);
    $category_description = trim($_POST["category_description"]);
    $category_status = $_POST["category_status"];

    if($category_title == ""){

        echo "Category title is required.";
        exit;
    }

    $query = "SELECT category_id FROM category WHERE category_title = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "s", $category_title);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){

        echo "Category already exists.";
        exit;
    }


    $query = "INSERT INTO category ( category_title, category_description, category_status) 
    VALUES ( ?, ?, ? )";

    $stmt = mysqli_prepare($conn, $query);

    // echo "</pre>";
    // print_r($_POST);
    // echo "</pre>";
    // die();

    mysqli_stmt_bind_param( $stmt, "sss", $category_title, $category_description, $category_status);

    if(mysqli_stmt_execute($stmt)){

        echo "success";
    }
    else{

        echo "Unable to add category.";
    }

}



// =====================Get Category===============================

else if(isset($_POST["get_category"])){

    // echo "</pre>";
    // print_r($_POST);
    // echo "</pre>";
    // die();

    $category_id = $_POST["category_id"];

    $query = "SELECT * FROM category WHERE category_id = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "i", $category_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $category = mysqli_fetch_assoc($result);

    // Return simple pipe-separated plain text instead of JSON
    echo $category["category_id"] . "|" . $category["category_title"] . "|" . $category["category_description"] . "|" . $category["category_status"];

}


//=================Update Category==================================


else if(isset($_POST["update_category"])){

    // echo "</pre>";
    // print_r($_POST);
    // echo "</pre>";
    // die();

    $category_id = $_POST["category_id"];
    $category_title = trim($_POST["category_title"]);
    $category_description = trim($_POST["category_description"]);
    $category_status = $_POST["category_status"];

    $query = "SELECT category_id FROM category WHERE category_title = ? AND category_id != ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param( $stmt, "si", $category_title, $category_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){

        echo "Category already exists.";
        exit;
    }


    $query = "UPDATE category SET category_title = ?, category_description = ?, category_status = ?, 
    updated_at = NOW() WHERE category_id = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param( $stmt, "sssi", $category_title, $category_description, $category_status, $category_id);

    if(mysqli_stmt_execute($stmt)){

        echo "success";
    }
    else{

        echo "Unable to update category.";
    }

}



// =================Delete Category===================================

else if(isset($_POST["delete_category"])){

    // echo "</pre>";
    // print_r($_POST);
    // echo "</pre>";
    // die();

    $category_id = $_POST["category_id"];

    $query = "DELETE FROM category WHERE category_id = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param( $stmt, "i", $category_id );

    if(mysqli_stmt_execute($stmt)){
        
        echo "success";
    }
    else{
        
        echo "Unable to delete category.";
    }

}



// ==================Activate / Deactivate Category==================================

else if(isset($_POST["update_category_status"])){

    // echo "</pre>";
    // print_r($_POST);
    // echo "</pre>";
    // die();

    $category_id = $_POST["category_id"];
    $category_status = $_POST["category_status"];

    $query = "UPDATE category SET category_status = ?, updated_at = NOW() WHERE category_id = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param( $stmt, "si", $category_status, $category_id);

    if(mysqli_stmt_execute($stmt)){
        echo "success";
    }
    else{
        echo "Unable to update category status.";
    }

}


// ====================Invalid Request================================

else{
    echo "Invalid request.";
}


?>