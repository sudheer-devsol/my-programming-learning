<?php
    /*
    IF-ELSE Statement

    if-else allows us to execute one block when a condition
    is true and another block when the condition is false.

    Syntax:

        if (condition) {
            // true block
        } else {
            // false block
        }
    */


    $age = 22;

    if ($age >= 18) {
        echo "You are eligible.";
    } else {
        echo "You are not eligible.";
    }

    echo "<br><br>";


    // Comparing Boolean Values

    if (false == 0 && true == 1) {
        echo "Both comparisons are true.";
    } else {
        echo "The condition is false.";
    }

    echo "<br><br>";


    // XOR with Conditions

    /*
    XOR returns true when exactly one condition is true.
    */

    if (-1 == true xor true == 1) {
        echo "Exactly one condition is true.";
    } else {
        echo "Both conditions have the same logical result.";
    }

    echo "<br><br>";


    // String and Boolean Comparison

    if ("-1" == true) {
        echo "The loose comparison is true.";
    } else {
        echo "The loose comparison is false.";
    }

    echo "<br><br>";


    // Combining NOT and Comparison

    if (!true == 0) {
        echo "The condition is true.";
    } else {
        echo "The condition is false.";
    }

    echo "<br><br>";


    // Practical Example

    $marks = 75;

    if ($marks >= 50) {
        echo "Student passed.";
    } else {
        echo "Student failed.";
    }
?>