<?php
    /*
    Constants: Constants are used to store values that should not
    be changed during the execution of the program.

    Unlike variables, constants do not use the $ sign.

    Constants can be created using define().

    Syntax:

        define("CONSTANT_NAME", value);

    Example:

        define("APP_NAME", "PHP Learning");
    */


    // Creating a Constant

    define("APP_NAME", "Sudheer PHP Learning");
    define("APP_VERSION", "1.0");
    define("MAX_USERS", 100);

    echo APP_NAME;
    echo "<br>";

    echo APP_VERSION;
    echo "<br>";

    echo MAX_USERS;
    echo "<br><br>";


    // Constants are normally written in uppercase

    /*
    Uppercase names are a common convention because they make
    constants easy to identify.
    */

    define("SITE_TITLE", "My PHP Repository");

    echo SITE_TITLE;
    echo "<br><br>";


    // Constant Names are Case-Sensitive

    /*
    These are two different constants:

        php
        PHP

    Constant names are case-sensitive when accessed.
    */

    define("php", "PHP Basic");
    define("PHP", "PHP Advanced");

    echo php;
    echo "<br>";

    echo PHP;
    echo "<br><br>";


    // Constant Names with Underscore

    define("_1234", "One Two Three Four");
    define("_0", "Zero");

    echo _1234;
    echo "<br>";

    echo _0;
    echo "<br><br>";


    // Constants Should Not Start With Numbers

    /*
    A constant should be given a valid identifier-style name.

    Examples that should not be used as normal constant names:

        1234
        9NAME

    Instead, use names such as:

        NUMBER_1234
        NAME_9
    */

    define("NUMBER_1234", "One Two Three Four");

    echo NUMBER_1234;
    echo "<br><br>";


    // Attempting to Define the Same Constant Again

    /*
    Once a constant has been defined, defining another value with
    the same name does not replace the original value.

    Therefore, always give constants unique names.
    */

    define("CUSTOMER_NAME", "Sudheer Mangi");

    // define("CUSTOMER_NAME", "Ali Khan");

    echo CUSTOMER_NAME;
    echo "<br><br>";


    // Dynamic Constant Name

    /*
    The name and value of a constant can also come from variables.

    This is useful when the constant name needs to be created
    dynamically.
    */

    $constantName = "FULL_NAME";
    $constantValue = "Sudheer Mangi";

    define($constantName, $constantValue);

    echo FULL_NAME;
    echo "<br><br>";


    // Using Constants in a Sentence

    echo "Welcome to " . APP_NAME . ". Version: " . APP_VERSION . ".";
?>