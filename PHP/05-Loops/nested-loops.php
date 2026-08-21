<?php
    /*
    NESTED LOOPS

    A nested loop means placing one loop inside another loop.

    The outer loop controls the larger repetition.

    The inner loop runs completely for every iteration
    of the outer loop.
    */


    // Alphabet with Numbers

    $letter = "a";

    for ($letter; $letter <= "z"; $letter++) {

        echo "Letter: " . $letter . " | Numbers: ";

        for ($number = 1; $number <= 10; $number++) {

            echo $number . " ";
        }

        echo "<br>";
    }

    echo "<hr>";


    // Reverse Alphabet with Numbers

    for ($letter = "Z"; $letter >= "A"; $letter--) {

        echo "Letter: " . $letter . " | Numbers: ";

        for ($number = 1; $number <= 10; $number++) {

            echo $number . " ";
        }

        echo "<br>";
    }

    echo "<hr>";


    // Number Table with Nested While Loop

    for ($number = 1; $number <= 10; $number++) {

        echo "Outer Number: " . $number . " | ";

        $counter = 1;

        while ($counter <= 10) {

            echo $counter . " ";

            $counter++;
        }

        echo "<br>";
    }

    echo "<hr>";


    // Nested Loop with an Additional While Loop

    /*
    Here three levels of repetition are demonstrated:

        Outer for loop
            ↓
        Inner for loop
            ↓
        while loop
    */

    for ($a = 1; $a <= 5; $a++) {

        echo "A: " . $a . " | ";

        for ($b = 1; $b <= 5; $b++) {

            echo "B: " . $b . " ";

        }

        $c = 1;

        while ($c <= $b) {

            echo "C: " . $c . " ";

            $c++;
        }

        echo "<br>";
    }

    echo "<hr>";


    // Alphabet and Numbers Using While Loops

    $letter = "a";

    while ($letter <= "y") {

        echo "Letter: " . $letter . " | ";

        $number = 1;

        while ($number <= 10) {

            echo $number . " ";

            $number++;
        }

        echo "<br>";

        $letter++;
    }


    /*
    Nested loops are commonly used for:

        -> Tables
        -> Patterns
        -> Matrix-like data
        -> Repeated groups of records
        -> Multi-dimensional arrays

    The important concept is that the inner loop completes
    its iterations for each iteration of the outer loop.
    */
?>