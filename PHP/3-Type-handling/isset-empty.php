<?php
    /*
    isset() and empty()

    These functions are commonly used to check variables
    before working with their values.
    */


    // isset()

    /*
    isset() checks whether a variable exists and its value
    is not NULL.

    It returns:

        true
        false
    */

    $data = "PHP";

    var_dump(isset($data));
    echo "<br><br>";


    // isset() with NULL

    $data = null;

    var_dump(isset($data));
    echo "<br><br>";


    // isset() with an Undefined Variable

    /*
    isset() can safely check a variable that has not been
    defined.
    */

    var_dump(isset($unknownData));
    echo "<br><br>";


    // isset() with Empty String

    $data = "";

    var_dump(isset($data));
    echo "<br><br>";


    // isset() with Zero

    $data = 0;

    var_dump(isset($data));
    echo "<br><br>";


    // empty()

    /*
    empty() checks whether a value is considered empty.

    Values commonly treated as empty include:

        ""
        "0"
        0
        0.0
        false
        null
        array()
        undefined variables
    */


    $data = "";

    var_dump(empty($data));
    echo "<br>";


    $data = 0;

    var_dump(empty($data));
    echo "<br>";


    $data = null;

    var_dump(empty($data));
    echo "<br>";


    $data = false;

    var_dump(empty($data));
    echo "<br>";


    $data = "PHP";

    var_dump(empty($data));
    echo "<br><br>";


    // Empty String vs Space

    /*
    An empty string has zero characters:

        ""

    A space is a character:

        " "

    Therefore, they are not the same.
    */

    $data = "";

    echo "Empty string: ";
    var_dump(empty($data));

    echo "<br>";

    $data = " ";

    echo "Space: ";
    var_dump(empty($data));

    echo "<br><br>";


    // isset() vs empty()

    $data = "PHP";

    echo "isset(): ";
    var_dump(isset($data));

    echo "<br>";

    echo "empty(): ";
    var_dump(empty($data));
?>