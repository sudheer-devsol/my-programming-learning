<?php
    /* 
    Constants: Constants are used to store data that cannot be changed
    after it has been defined.

    Unlike variables, constants do not use the $ sign.

    -> Creating Constants

    We can create constants using the define() function.

    Syntax:
    define("CONSTANT_NAME", value);

    Example:
    define("SITE_NAME", "My Website");

    Note: Constant names are usually written in uppercase letters
    to make them easy to identify.

    -> Constant Naming Rules

    Valid way to define constants:
    * SITE_NAME
    * PI
    * MAX_USERS
    * COMPANY_NAME
    * DATABASE_NAME

    Invalid way to define constants:
    * 9NAME
    * SITE-NAME
    * SITE NAME

    Rules:
        Constant names cannot contain spaces.
        Constant names should not start with a number.
        Constant names are commonly written in uppercase.
        Constants do not use the $ sign.
    */

    // Creating Constants

    define("SITE_NAME", "PHP Learning Repository");
    define("VERSION", "1.0");
    define("MAX_USERS", 100);

    // Printing Constants

    echo SITE_NAME;
    echo "<br>";

    echo VERSION;
    echo "<br>";

    echo MAX_USERS;
    echo "<br>";

    // We can also use constants inside a sentence

    echo "Welcome to " . SITE_NAME . ". Current version is " . VERSION . ".";
?>