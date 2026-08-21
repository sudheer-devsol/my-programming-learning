<?php
    /*
    Nested IF

    A nested if means placing one if statement inside another if.

    This is useful when the second condition should only be
    checked after the first condition has passed.
    */


    $number = 20;

    if ($number > 5) {

        echo "Outer IF condition is true.";
        echo "<br>";

        if ($number === 20) {
            echo "Inner IF condition is true.";
        } else {
            echo "Inner IF condition is false.";
        }

    } else {

        echo "Outer IF condition is false.";

    }

    echo "<br><br>";


    // Nested IF with AND and OR

    $age = 22;
    $hasId = true;

    if ($age >= 18 && $hasId) {

        echo "Age and ID requirements passed.";
        echo "<br>";

        if ($age >= 21 || $hasId == true) {
            echo "Inner condition also passed.";
        } else {
            echo "Inner condition failed.";
        }

    } else {

        echo "Basic requirements failed.";

    }


    /*
    Nested conditions should be used when they make the logic
    easier to understand.

    Too many levels of nesting can make code difficult to maintain.
    */
?>