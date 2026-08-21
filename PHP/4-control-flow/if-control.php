<?php
    /*
    IF Statement

    The if statement is used to execute a block of code
    when a condition is true.

    Syntax:

        if (condition) {
            // code
        }

    If the condition is true, PHP executes the code
    inside the if block.

    If the condition is false, PHP skips the block.
    */


    // Condition is FALSE

    if (false) {
        echo "This condition is true.";
    }

    echo "<br>";


    // Condition is TRUE

    if (true) {
        echo "This condition is true.";
    }

    echo "<br>";


    // Using NOT with a Condition

    /*
    ! changes a boolean value to its opposite.

        !false = true
        !true  = false
    */

    if (!false) {
        echo "The condition became true.";
    }

    echo "<br>";


    // String as a Condition

    /*
    PHP can evaluate values as true or false when they
    are used as conditions.

    A non-empty string is generally treated as true,
    except for the special string "0".
    */

    if ("PHP") {
        echo "A non-empty string is treated as true.";
    }

    echo "<br><br>";


    // String "0" as a Condition

    if ("0") {
        echo "The string 0 is true.";
    } else {
        echo "The string 0 is treated as false.";
    }

    echo "<br><br>";


    // String "0abc"

    /*
    "0abc" is not the same as the string "0".

    As a condition, it is a non-empty string and therefore
    evaluates as true.
    */

    if ("0abc") {
        echo "The string 0abc is treated as true.";
    } else {
        echo "The condition is false.";
    }
?>