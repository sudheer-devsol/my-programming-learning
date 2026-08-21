<?php
    /*
    FOR LOOP

    A for loop is commonly used when we know how many times
    we want to repeat something.

    Syntax:

        for (initialization; condition; increment) {
            // code
        }

    Example:

        for ($a = 1; $a <= 10; $a++) {
            ...
        }
    */


    // Basic For Loop

    for ($number = 1; $number <= 10; $number++) {

        echo $number . " ";
    }

    echo "<hr>";


    // Increment by 2

    for ($number = 1; $number <= 100; $number += 2) {

        echo $number . " ";
    }

    echo "<hr>";


    // Increment by 3

    for ($number = 1; $number <= 100; $number += 3) {

        echo $number . " ";
    }

    echo "<hr>";


    // Odd Numbers

    /*
    The modulus operator helps determine whether a number
    is odd or even.

        odd number % 2 = 1
        even number % 2 = 0
    */

    for ($number = 1; $number <= 100; $number++) {

        if ($number % 2 == 1) {
            echo $number . " ";
        }
    }

    echo "<hr>";


    // Counting Backwards

    for ($number = 10; $number >= 1; $number--) {

        echo $number . " ";
    }

    echo "<hr>";


    // Omitting the Initialization

    $number = 1;

    for (; $number <= 10; $number++) {

        echo $number . " ";
    }

    echo "<hr>";


    // Omitting the Initialization and Increment

    $number = 1;

    for (; $number <= 5;) {

        echo $number . " ";

        $number++;
    }

    echo "<hr>";


    // Infinite For Loop

    /*
    An empty for loop condition creates an infinite loop:

        for (;;) {
        }

    It is intentionally not executed here because it would
    continue forever.
    */


    // Character Loop

    /*
    PHP can increment alphabetic strings.

    For example:

        A
        B
        C
        ...

    This behavior should be understood as a PHP feature,
    not confused with numeric incrementing.
    */

    $letter = "A";

    for ($letter; $letter <= "Z"; $letter++) {

        echo $letter . " ";
    }

    echo "<hr>";


    // Lowercase Letters

    $letter = "a";

    for ($letter; $letter <= "z"; $letter++) {

        echo $letter . " ";
    }
?>