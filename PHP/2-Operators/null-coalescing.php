<?php
    /*
    Null Coalescing Operator

    The null coalescing operator is:

        ??

    It checks whether a value exists and is not NULL.

    Syntax:

        $value ?? "default value";

    If $value exists and is not NULL:
        -> its value is returned.

    If it does not exist or is NULL:
        -> the default value is returned.
    */


    // Variable Exists

    $data = 5;

    echo $data ?? "Variable value not set";

    echo "<br><br>";


    // Variable is NULL

    $data = null;

    echo $data ?? "Variable value not set";

    echo "<br><br>";


    // Variable is an Empty String

    $data = "";

    echo $data ?? "Variable value not set";

    echo "<br><br>";


    // Variable is Zero

    $data = 0;

    echo $data ?? "Variable value not set";

    echo "<br><br>";


    // Variable is False

    $data = false;

    var_dump($data ?? "Variable value not set");

    echo "<br><br>";


    // Undefined Variable

    /*
    The null coalescing operator is useful when a variable
    may not exist.

    It allows us to provide a fallback value.
    */

    echo $unknownData ?? "Variable value not set";

    echo "<br><br>";


    // Comparison with isset()

    $data = "PHP";

    echo isset($data)
        ? $data
        : "Variable value not set";

    echo "<br>";

    echo $data ?? "Variable value not set";
?>