<?php
    /*
    Ternary Operator

    The ternary operator is a short way of writing a simple
    if-else condition.

    Syntax:

        condition ? value_if_true : value_if_false;
    */


    // Basic Ternary Example

    echo (1 > 2) ? "Hi" : "Hello";

    echo "<br><br>";


    // Using NOT with Ternary

    echo !(true) ? "Hi" : "Hello";

    echo "<br><br>";


    // Using XOR with Ternary

    echo !(true xor 1) ? "Hi" : "Hello";

    echo "<br><br>";


    // Ternary with AND

    echo (1 < 2 && 2 == "2") ? "Hi" : "Hello";

    echo "<br><br>";


    // Ternary with a False Condition

    echo (0 && (1 < 2 && 2 == "2"))
        ? "Hi"
        : "Hello";

    echo "<br><br>";


    // Ternary with Variables

    $number = 5;

    echo ($number == 5)
        ? "Number is 5"
        : "Number is not 5";

    echo "<br><br>";


    // Ternary with Strict Comparison

    echo ($number === "5")
        ? "Value and type are identical"
        : "Value or type is different";
?>