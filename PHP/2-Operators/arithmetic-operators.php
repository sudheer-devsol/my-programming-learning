<?php
    /*
    Arithmetic Operators: Arithmetic operators are used to perform
    mathematical calculations.

    Common arithmetic operators:

        +    Addition
        -    Subtraction
        *    Multiplication
        /    Division
        %    Modulus
    */


    $number1 = 5;
    $number2 = 6;


    // Addition

    $result = $number1 + $number2;

    echo "Addition: " . $result;
    echo "<br>";


    // Subtraction

    $result = $number1 - $number2;

    echo "Subtraction: " . $result;
    echo "<br>";


    // Multiplication

    $result = $number1 * $number2;

    echo "Multiplication: " . $result;
    echo "<br>";


    // Division

    $result = $number1 / $number2;

    echo "Division: " . $result;
    echo "<br>";


    // Modulus

    /*
    Modulus (%) returns the remainder after division.

    Example:
        10 % 3 = 1
    */

    $result = 10 % 3;

    echo "Remainder: " . $result;
    echo "<br><br>";


    // Arithmetic with Numeric Strings

    /*
    PHP can convert a numeric string to a number in arithmetic
    operations.

    Example:
        5 + "1" results in 6.
    */

    $data = 5 + "1";

    echo $data;
    echo "<br><br>";


    /*
    A non-numeric string should not be used as a number.

    In modern PHP versions, an operation such as:

        "number" + "2"

    causes a TypeError because "number" is not a numeric value.
    */

    // $data = "number" + "2";
?>