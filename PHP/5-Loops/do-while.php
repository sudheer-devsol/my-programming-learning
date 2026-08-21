<?php

/*
    DO-WHILE LOOP

    A do-while loop is used when a block of code must execute
    at least once before the condition is checked.

    Structure:

        do {
            // code
        } while (condition);

    Important difference:

        while loop
            -> condition is checked first
            -> code may execute zero times

        do-while loop
            -> code executes first
            -> condition is checked afterward
            -> code executes at least once
*/


/* =========================================================
   1. BASIC DO-WHILE LOOP
   ========================================================= */

$number = 1;

do {

    echo $number . " ";

    $number++;

} while ($number <= 5);

echo "<hr>";



/* =========================================================
   2. DO-WHILE WITH A CONDITION THAT IS FALSE
   ========================================================= */

/*
    Even though the condition is false from the beginning,
    the code inside the do block executes once.

    This is the main behavior that makes do-while different
    from while.
*/

$number = 10;

do {

    echo "This code executes once.";

} while ($number < 5);

echo "<hr>";



/* =========================================================
   3. COMPARISON WITH WHILE LOOP
   ========================================================= */

/*
    In a while loop, the condition is checked before execution.

    Since 10 is not less than 5, nothing is printed.
*/

$number = 10;

while ($number < 5) {

    echo "This will not execute.";
}

echo "While loop finished.";

echo "<hr>";



/*
    In a do-while loop, the code executes before checking
    the condition.

    Therefore, the message is printed once.
*/

$number = 10;

do {

    echo "Do-while executes once.";

} while ($number < 5);

echo "<hr>";



/* =========================================================
   4. COUNTING FORWARD
   ========================================================= */

$count = 1;

do {

    echo "Count: " . $count;
    echo "<br>";

    $count++;

} while ($count <= 10);

echo "<hr>";



/* =========================================================
   5. COUNTING BACKWARD
   ========================================================= */

$count = 5;

do {

    echo "Countdown: " . $count;
    echo "<br>";

    $count--;

} while ($count >= 1);

echo "<hr>";



/* =========================================================
   6. DO-WHILE WITH A CONDITION
   ========================================================= */

/*
    The condition can contain comparison operators.
*/

$marks = 85;

do {

    echo "Student marks: " . $marks;

    $marks++;

} while ($marks < 90);

echo "<hr>";



/* =========================================================
   7. DO-WHILE WITH LOGICAL OPERATORS
   ========================================================= */

$number = 1;

do {

    echo $number . " ";

    $number++;

} while ($number <= 5 && $number > 0);

echo "<hr>";



/* =========================================================
   8. DO-WHILE WITH IF
   ========================================================= */

/*
    A do-while loop can contain other control structures
    such as if/else.
*/

$number = 1;

do {

    if ($number % 2 == 0) {

        echo $number . " is even";

    } else {

        echo $number . " is odd";
    }

    echo "<br>";

    $number++;

} while ($number <= 10);

echo "<hr>";



/* =========================================================
   9. DO-WHILE WITH BREAK
   ========================================================= */

/*
    break completely terminates the do-while loop.
*/

$number = 1;

do {

    if ($number == 5) {
        break;
    }

    echo $number . " ";

    $number++;

} while ($number <= 10);

echo "<hr>";



/* =========================================================
   10. DO-WHILE WITH CONTINUE
   ========================================================= */

/*
    continue skips the current iteration and moves toward
    the next iteration.

    Important:

    When using continue in a do-while loop, make sure the
    variable used in the condition is updated correctly.
*/

$number = 1;

do {

    $number++;

    if ($number == 5) {
        continue;
    }

    echo $number . " ";

} while ($number <= 10);

echo "<hr>";



/* =========================================================
   11. DO-WHILE WITH MULTIPLE CONDITIONS
   ========================================================= */

$number = 1;

do {

    echo "Number: " . $number;
    echo "<br>";

    $number++;

} while ($number <= 10 && $number != 7);

echo "<hr>";



/* =========================================================
   12. NESTED DO-WHILE LOOPS
   ========================================================= */

/*
    A do-while loop can be placed inside another do-while
    loop.

    This is called a nested loop.
*/

$outer = 1;

do {

    echo "Outer: " . $outer . "<br>";

    $inner = 1;

    do {

        echo "Inner: " . $inner . "<br>";

        $inner++;

    } while ($inner <= 3);

    echo "<br>";

    $outer++;

} while ($outer <= 3);

echo "<hr>";



/* =========================================================
   13. PRACTICAL EXAMPLE
   ========================================================= */

/*
    Imagine a system that needs to display a message at least
    once and continue while the number of attempts is below 3.
*/

$attempt = 1;

do {

    echo "Attempt number: " . $attempt;
    echo "<br>";

    $attempt++;

} while ($attempt <= 3);

echo "<hr>";



/* =========================================================
   14. DO-WHILE WITH BOOLEAN CONDITION
   ========================================================= */

$number = 1;

do {

    echo "Processing number: " . $number;
    echo "<br>";

    $number++;

} while (true && $number <= 5);



/*
    SUMMARY

    do-while syntax:

        do {
            // code
        } while (condition);

    Key points:

        1. The code executes before the condition is checked.

        2. Therefore, a do-while loop always executes
           at least once.

        3. The condition is checked after every iteration.

        4. break completely terminates the loop.

        5. continue skips the current iteration.

        6. do-while loops can be nested inside other loops.

    Main difference:

        while:
            condition -> code

        do-while:
            code -> condition
*/

?>