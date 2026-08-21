<?php
    include_once("db.php");

    $query = "SELECT * FROM students";
    $result = mysqli_query($connect, $query);
    $action = false;

    if (isset($_REQUEST['update'])) {

        $id = $_REQUEST['update'];

        $query = "SELECT * FROM students WHERE id = $id";

        $student_result = mysqli_query($connect, $query);

        if ($student_result) {

            $student = mysqli_fetch_assoc($student_result);

            $action = true;
        
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD APP</title>

    <style>
        .Container{
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            margin: 20px;
            flex-direction: column;
        }
        .Form-box{
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px;
            padding: 30px;
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <div class="Container">
    
    <div class="Form-box">
    <form action="process.php" method="POST">

        <?php if ($action) { ?>

            <input type="hidden" name="id" value="<?= $student['id'] ?>">

        <?php } ?>

        <table>

            <thead>
                <tr>
                    <th colspan="6">
                        <?= $action ? "Update" : "Add" ?> Student
                    </th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>First Name</td>
                    <td>
                        <input
                            type="text"
                            name="firstName"
                            value="<?= $action ? $student['name'] : "" ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <td>Email</td>
                    <td>
                        <input
                            type="email"
                            name="email"
                            value="<?= $action ? $student['email'] : "" ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <td>Phone</td>
                    <td>
                        <input
                            type="text"
                            name="phone"
                            value="<?= $action ? $student['phone'] : "" ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <td>Course</td>

                    <td>
                        <select name="course" id="course">

                            <option value="">
                                -----Select Course------
                            </option>

                            <option value="PHP basic"
                                <?= $action && $student['course'] == "PHP basic" ? "selected" : "" ?>>
                                PHP basic
                            </option>

                            <option value="PHP Advance"
                                <?= $action && $student['course'] == "PHP Advance" ? "selected" : "" ?>>
                                PHP Advance
                            </option>

                            <option value="Laravel"
                                <?= $action && $student['course'] == "Laravel" ? "selected" : "" ?>>
                                Laravel
                            </option>

                        </select>
                    </td>
                </tr>

                <tr>
                    <td>

                        <input
                            type="submit"
                            name="submit"
                            value="<?= $action ? "Update" : "Add" ?> Record"
                        >

                        <input type="reset" value="Cancel">

                    </td>
                </tr>

            </tbody>

        </table>

    </form>
    </div>

    <table border="1">

    <thead>
        <tr>
            <th colspan="6">Registered Student</th>
        </tr>

        <tr>
            <th>Sr.No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
       
            <?php
                while($row = mysqli_fetch_assoc($result)){
                ?>
                    <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['course'] ?></td>
                    <td>
                        <a href="?update=<?= $row['id'] ?>">Edit</a>
                        <a href="process.php?delete=<?= $row['id'] ?>" onclick="return confirm('Do you want to delete this student record?')">Delete</a>
                        
                    </td>
                    </tr>
                <?php  
                }
            ?>
          
    </tbody>

    </table>

    </div>
</body>
</html>

