<?php
    /*
    ELSEIF

    elseif allows us to check multiple conditions.

    PHP checks the conditions from top to bottom.

    As soon as one condition is true, its block executes
    and the remaining conditions are skipped.

    Syntax:

        if (condition) {
            ...
        } elseif (condition) {
            ...
        } else {
            ...
        }
    */


    // Grade Example

    $marks = 75;

    if ($marks >= 90 && $marks <= 100) {
        echo "Superb";
    } elseif ($marks >= 80 && $marks < 90) {
        echo "Excellent";
    } elseif ($marks >= 70 && $marks < 80) {
        echo "Good";
    } elseif ($marks >= 60 && $marks < 70) {
        echo "Average";
    } elseif ($marks >= 50 && $marks < 60) {
        echo "Pass";
    } else {
        echo "Failed";
    }

    echo "<br><br>";


    // Multiple Conditions

    $number = 10;

    if ($number > 20) {
        echo "Greater than 20.";
    } elseif ($number == 10) {
        echo "The number is 10.";
    } else {
        echo "Another value.";
    }

    echo "<br><br>";


    // Complex Condition

    /*
    Parentheses make complex conditions easier to understand.
    */

    $number = 20;

    if (!true == false && $number > 5) {
        echo "First condition is true.";
    } else {
        echo "First condition is false.";
    }
?>