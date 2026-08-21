<?php
    /*
    Assignment Operators: Assignment operators are used to assign
    values to variables.

    Basic assignment:

        =

    Compound assignment operators:

        +=
        -=
        *=
        /=
    */


    // Basic Assignment

    $number = 5;

    echo $number;
    echo "<br><br>";


    // Addition Assignment

    /*
    $number += 5;

    is the same as:

    $number = $number + 5;
    */

    $number = 5;

    $number += 5;

    echo "After += 5: " . $number;
    echo "<br>";


    $number += 7;

    echo "After += 7: " . $number;
    echo "<br><br>";


    // Subtraction Assignment

    $number = 5;

    $number -= 1;

    echo "After -= 1: " . $number;
    echo "<br>";

    $number -= 2;

    echo "After -= 2: " . $number;
    echo "<br><br>";


    // Multiplication Assignment

    $number = 5;

    $number *= 5;

    echo "After *= 5: " . $number;
    echo "<br><br>";


    // Division Assignment

    $number = 25;

    $number /= 5;

    echo "After /= 5: " . $number;
    echo "<br><br>";


    // Multiple Assignment Operations

    $number = 5;

    $number += 5;
    echo $number;
    echo "<br>";

    $number -= 5;
    echo $number;
    echo "<br>";

    $number *= 5;
    echo $number;
    echo "<br>";

    $number /= 5;
    echo $number;
?>