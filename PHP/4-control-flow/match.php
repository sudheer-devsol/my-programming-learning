<?php
    /*
    MATCH EXPRESSION

    match is available in modern PHP versions and provides
    another way to select a result based on a value.

    Unlike switch, match:

        -> Returns a value.
        -> Uses strict comparison.
        -> Does not require break.
        -> Can have a default case.
    */


    // Basic Match

    $number = 3;

    $result = match ($number) {

        1 => "It is one",
        2 => "It is two",
        3 => "It is three",
        4 => "It is four",
        5 => "It is five",

        default => "Number not matched"
    };

    echo $result;
    echo "<br><br>";


    // Match with Multiple Values

    $student = "Sudheer";

    $course = match ($student) {

        "Sudheer", "Ali", "Khan" => "PHP Basic",
        "Ahmed" => "Java",
        "Mohsin" => "PHP Advanced",
        "Fahad" => "Database",

        default => "Record not matched"
    };

    echo $course;
    echo "<br><br>";


    // Match Default Case

    $student = "Ahsan";

    echo match ($student) {

        "Sudheer", "Ali", "Khan" => "PHP Basic",
        "Ahmed" => "Java",
        "Mohsin" => "PHP Advanced",
        "Fahad" => "Database",

        default => "Record not matched"
    };

    echo "<br><br>";


    /*
    MATCH uses strict comparison.

    For example:

        1

    and:

        "1"

    are different values because their data types are different.
    */

    $value = "1";

    echo match ($value) {

        1 => "Integer 1",
        "1" => "String 1",

        default => "No match"
    };
?>