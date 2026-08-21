<?php
    /*
    Increment and Decrement Operators

    Increment means increasing a value by 1.

        ++

    Decrement means decreasing a value by 1.

        --

    PHP supports:

        -> Post-increment
        -> Pre-increment
        -> Post-decrement
        -> Pre-decrement
    */


    // Increment

    $number = 5;

    $number = $number + 1;

    echo $number;
    echo "<br>";

    $number++;

    echo $number;
    echo "<br>";

    ++$number;

    echo $number;
    echo "<br><br>";


    // Decrement

    $number = 5;

    $number = $number - 1;

    echo $number;
    echo "<br>";

    $number--;

    echo $number;
    echo "<br>";

    --$number;

    echo $number;
    echo "<br><br>";


    // Post-Increment

    /*
    With post-increment:

        $number++

    PHP first uses the current value and then increases it.
    */

    $number = 5;

    echo $number++;
    echo "<br>";

    echo $number;
    echo "<br><br>";


    // Pre-Increment

    /*
    With pre-increment:

        ++$number

    PHP increases the value first and then uses the new value.
    */

    $number = 5;

    echo ++$number;
    echo "<br>";

    echo $number;
    echo "<br><br>";


    // Post-Decrement

    $number = 5;

    echo $number--;
    echo "<br>";

    echo $number;
    echo "<br><br>";


    // Pre-Decrement

    $number = 5;

    echo --$number;
    echo "<br>";

    echo $number;
    echo "<br><br>";


    // Incrementing Multiple Times

    $number = 5;

    $number++;
    $number++;
    $number++;

    echo $number;
    echo "<br><br>";


    // Increment in an Expression

    /*
    This example demonstrates the difference between
    post-increment and pre-increment.

    Starting value = 5

    $number++ returns 5 and then becomes 6.

    ++$number increases it to 7 and returns 7.

    Therefore:

        5 + 7 = 12
    */

    $number = 5;

    echo $number++ + ++$number;
    echo "<br>";

    echo $number;
    echo "<br><br>";


    // Decrement in an Expression

    /*
    Starting value = 5

    $number-- returns 5 and then becomes 4.

    --$number changes it to 3 and returns 3.

    Therefore:

        5 - 3 = 2
    */

    $number = 5;

    echo $number-- - --$number;
    echo "<br>";

    echo $number;
?>