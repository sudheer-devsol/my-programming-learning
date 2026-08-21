<?php
    /*
    Data Types: A data type tells PHP what kind of value
    is stored in a variable.

    Common PHP data types include:

        1. String
        2. Integer
        3. Float
        4. Boolean
        5. Array
        6. NULL

    PHP is dynamically typed, which means the same variable
    can hold different types of values at different times.
    */


    // String

    $data = "PHP Learning";

    echo $data;
    echo "<br>";

    var_dump($data);
    echo "<br><br>";


    // Integer

    $data = 780;

    echo $data;
    echo "<br>";

    var_dump($data);
    echo "<br><br>";


    // Float

    $data = 45.67;

    echo $data;
    echo "<br>";

    var_dump($data);
    echo "<br><br>";


    // Boolean

    $data = true;

    var_dump($data);
    echo "<br>";

    $data = false;

    var_dump($data);
    echo "<br><br>";


    // NULL

    $data = null;

    var_dump($data);
    echo "<br><br>";


    // Empty String

    $data = "";

    var_dump($data);
    echo "<br><br>";


    // Array

    $data = array(10, 20, "PHP", 10.98);

    var_dump($data);
    echo "<br><br>";


    // Checking Data Type with gettype()

    /*
    gettype() returns the data type of a variable as a string.
    */

    $data = 90;

    echo gettype($data);
    echo "<br>";

    $data = 78.09;

    echo gettype($data);
    echo "<br>";

    $data = "PHP";

    echo gettype($data);
    echo "<br>";

    $data = true;

    echo gettype($data);
    echo "<br>";

    $data = null;

    echo gettype($data);
    echo "<br><br>";


    // Changing Data Type with settype()

    /*
    settype() changes the data type of an existing variable.

    The original value may be converted according to the
    requested data type.
    */

    $data = 50;

    echo gettype($data);
    echo "<br>";

    settype($data, "string");

    echo gettype($data);
    echo "<br><br>";


    $data = "50";

    echo gettype($data);
    echo "<br>";

    settype($data, "integer");

    echo gettype($data);
    echo "<br><br>";


    // One Variable Can Hold Different Data Types

    /*
    PHP does not require us to permanently declare a variable
    as one data type.

    The variable can be reassigned with another type.
    */

    $data = "PHP";
    var_dump($data);

    echo "<br>";

    $data = 100;
    var_dump($data);

    echo "<br>";

    $data = 50.5;
    var_dump($data);

    echo "<br>";

    $data = false;
    var_dump($data);

    echo "<br>";

    $data = null;
    var_dump($data);
?>