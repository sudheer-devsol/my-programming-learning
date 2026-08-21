<?php

include_once("db.php");


if (isset($_REQUEST['submit'])) {

    $name = $_REQUEST['firstName'];
    $email = $_REQUEST['email'];
    $phone = $_REQUEST['phone'];
    $course = $_REQUEST['course'];

    if (isset($_REQUEST['id'])) {

        $id = $_REQUEST['id'];

        $query = "UPDATE students SET name = '$name', email = '$email',  phone = '$phone', course = '$course' WHERE id = $id";

        if (mysqli_query($connect, $query)) {

            header("location: index.php?success-mesg=Student Record Updated successfully");
            exit;
        }

    } else {

        $query = "INSERT INTO students
                    (name, email, phone, course)
                  VALUES
                    ('$name', '$email', '$phone', '$course')";

        if (mysqli_query($connect, $query)) {

            header("location: index.php?success-mesg=Student Record Added successfully");
            exit;
        }
    }
}
elseif (isset($_REQUEST['delete'])) {

    $id = $_REQUEST['delete'];

    $query = "DELETE FROM students WHERE id = '$id'";

    if (mysqli_query($connect, $query)) {

        header("location: index.php?success-mesg=Student Record Deleted successfully");
        exit;
    }
}

?>