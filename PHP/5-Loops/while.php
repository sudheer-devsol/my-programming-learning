<?php
    /*
    WHILE LOOP

    A while loop repeatedly executes code while a condition
    remains true.

    Syntax:

        while (condition) {
            // code
        }

    The condition is checked before every iteration.
    */


    // Basic While Loop

    $number = 0;

    while ($number <= 10) {

        echo "Number: " . $number;
        echo "<br>";

        $number++;
    }

    echo "<hr>";


    // Counting Backwards

    $number = 10;

    while ($number >= 0) {

        echo "Number: " . $number;
        echo "<br>";

        $number--;
    }

    echo "<hr>";


    // Increment Inside the Condition

    /*
    ++$number increases the value before it is evaluated.
    */

    $number = 0;

    while (++$number && $number <= 10) {

        echo "Number: " . $number;
        echo "<br>";
    }

    echo "<hr>";


    // Post-Increment in a Condition

    /*
    With $number++, the current value is evaluated first
    and then the variable is incremented.
    */

    $number = 1;

    while ($number++ && $number <= 10) {
        echo "Number: " . $number;
        echo "<br>";
    }

    echo "<hr>";


    // Using isset()

    $number = 1;

    while (isset($number) && $number <= 10) {

        echo "Number: " . $number;
        echo "<br>";

        $number++;
    }

    echo "<hr>";


    // Alternative Syntax

    $number = 1;

    while (isset($number) && $number <= 5):

        echo "Number: " . $number;
        echo "<br>";

        $number++;

    endwhile;

    echo "<hr>";


    // NULL and NOT

    /*
    NULL is false in a boolean context.

    Therefore:

        !NULL

    becomes true.
    */

    while (!NULL) {

        echo "This would create an infinite loop.";

        /*
        This example is intentionally not executed because
        the condition never becomes false.
        */

        break;
    }


    /*
    Important:

    Every loop should eventually make its condition false.

    Otherwise, the loop can become infinite.
    */


    // Two Variables Changing Together

    $a = 1;
    $b = 5;

    while ($a <= $b) {

        echo "A: " . $a . " | B: " . $b;
        echo "<br>";

        $a++;
        $b--;
    }
?>