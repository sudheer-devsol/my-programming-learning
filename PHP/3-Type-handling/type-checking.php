<?php
    /*
    Type Checking

    PHP provides several built-in functions for checking
    the type or nature of a value.

    Common functions include:

        gettype()
        is_array()
        is_bool()
        is_float()
        is_int()
        is_null()
        is_numeric()
        is_scalar()
        is_string()
    */


    // gettype()

    $data = 50;

    echo gettype($data);
    echo "<br><br>";


    // is_array()

    $data = array(10, 20, 30);

    var_dump(is_array($data));
    echo "<br>";


    // is_bool()

    $data = false;

    var_dump(is_bool($data));
    echo "<br>";


    // is_float()

    $data = 45.67;

    var_dump(is_float($data));
    echo "<br>";


    // is_int()

    $data = 780;

    var_dump(is_int($data));
    echo "<br>";


    // is_null()

    $data = null;

    var_dump(is_null($data));
    echo "<br>";


    // is_numeric()

    /*
    is_numeric() checks whether a value is a number or
    a numeric string.
    */

    $data = "780";

    var_dump(is_numeric($data));
    echo "<br>";

    $data = 780;

    var_dump(is_numeric($data));
    echo "<br>";


    // is_scalar()

    /*
    Scalar values include:

        -> Integer
        -> Float
        -> String
        -> Boolean
    */

    $data = "PHP";

    var_dump(is_scalar($data));
    echo "<br>";

    $data = array(10, 20);

    var_dump(is_scalar($data));
    echo "<br>";


    // is_string()

    $data = "PHP Learning";

    var_dump(is_string($data));
    echo "<br><br>";


    // Checking Multiple Values

    $values = array(
        10,
        45.67,
        "780",
        "PHP",
        true,
        false,
        null,
        array(1, 2, 3)
    );

    foreach ($values as $value) {
        echo gettype($value);
        echo "<br>";
    }
?>