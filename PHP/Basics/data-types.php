<?php
    /* 
    Data Types: Data types define what kind of data a variable contains.

    PHP supports several data types.

    Common PHP data types are:

    1. String
    2. Integer
    3. Float
    4. Boolean
    5. Array
    6. NULL
    7. Object
    8. Resource

    In this file, we will understand the basic data types
    with simple examples.
    */


    // String
    // A string is a sequence of characters or text.

    $name = "Ali";
    $course = "PHP";

    echo $name;
    echo "<br>";

    echo $course;
    echo "<br><br>";


    // Integer
    // An integer is a whole number without a decimal point.

    $age = 20;
    $students = 50;

    echo $age;
    echo "<br>";

    echo $students;
    echo "<br><br>";


    // Float
    // A float is a number that contains a decimal point.

    $marks = 88.5;
    $price = 1500.75;

    echo $marks;
    echo "<br>";

    echo $price;
    echo "<br><br>";


    // Boolean
    // A boolean can have only two values: true or false.

    $isPass = true;
    $isFail = false;

    echo $isPass;
    echo "<br>";

    echo $isFail;
    echo "<br><br>";


    // NULL
    // NULL means that a variable has no value.

    $data = null;

    var_dump($data);
    echo "<br><br>";


    // Array
    // An array is used to store multiple values in one variable.

    $subjects = array("PHP", "HTML", "CSS", "JavaScript");

    var_dump($subjects);
    echo "<br><br>";


    // We can use var_dump() to check the data type and value.

    var_dump($name);
    echo "<br>";

    var_dump($age);
    echo "<br>";

    var_dump($marks);
    echo "<br>";

    var_dump($isPass);
    echo "<br>";

    var_dump($data);
?>