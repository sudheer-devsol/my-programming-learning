<?php
    /*
    Comparison Operators: Comparison operators compare two values.

    Common comparison operators:

        >     Greater than
        <     Less than
        >=    Greater than or equal to
        <=    Less than or equal to
        ==    Equal
        ===   Identical
        !=    Not equal
        !==   Not identical

    Comparison expressions return a Boolean value:

        true
        false
    */


    // Greater Than

    echo 5 > 1;
    echo "<br>";


    // Less Than

    echo 5 < 1;
    echo "<br>";


    // Greater Than or Equal To

    echo 5 >= 5;
    echo "<br>";


    // Less Than or Equal To

    echo 5 <= 1;
    echo "<br><br>";


    // Loose Comparison ==

    /*
    == compares values after PHP performs the required
    type conversion.

    Example:

        5 == "5"

    is true because the values are considered equal after
    type conversion.
    */

    $data = 5;

    var_dump($data == "5");
    echo "<br><br>";


    // Strict Comparison ===

    /*
    === compares both:

        -> Value
        -> Data type

    Therefore:

        5 === "5"

    is false because one is an integer and the other is a string.
    */

    var_dump($data === "5");
    echo "<br><br>";


    // Not Equal

    var_dump($data != 4);
    echo "<br>";

    var_dump($data != 5);
    echo "<br>";

    var_dump($data != "4");
    echo "<br>";

    var_dump($data != "5");
    echo "<br><br>";


    // Strict Not Identical

    var_dump($data !== "5");
    echo "<br><br>";


    // More Comparison Examples

    var_dump(10 == "10");
    echo "<br>";

    var_dump(10 === "10");
    echo "<br>";

    var_dump(10 != "20");
    echo "<br>";

    var_dump(10 !== "10");
?>