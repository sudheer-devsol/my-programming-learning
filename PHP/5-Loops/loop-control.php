<?php

/*
    LOOP CONTROL

    PHP provides statements that allow us to control the
    execution of a loop.

    The main loop-control statements are:

        break
        continue

    break:
        Stops the loop completely.

    continue:
        Skips the current iteration and moves to the
        next iteration.

    These statements become especially important when
    working with nested loops.
*/


/* =========================================================
   1. BREAK
   ========================================================= */

/*
    break immediately terminates the current loop.

    Example:

        The loop starts from 1.
        When the value reaches 8, break stops the loop.

    Therefore, 8 and the remaining values are not printed.
*/

for ($number = 1; $number <= 10; $number++) {

    if (($number * $number) == 64) {
        break;
    }

    echo ($number * $number) . " ";
}

echo "<hr>";



/* =========================================================
   2. BREAK INSIDE A NESTED LOOP
   ========================================================= */

/*
    When break is used without a number, it terminates
    only the nearest/current loop.

    Here:

        for = outer loop
        while = inner loop

    The break belongs to the while loop, so only the
    while loop is stopped.
*/

for ($outer = 1; $outer <= 5; $outer++) {

    $inner = 1;

    while ($inner <= $outer) {

        $inner++;

        if ($inner == 4) {
            break;
        }

        echo "Outer: " . $outer . " | Inner: " . $inner;
        echo "<br>";
    }
}

echo "<hr>";



/* =========================================================
   3. BREAK 1
   ========================================================= */

/*
    break 1 means:

        Break out of one loop level.

    This is effectively the same as:

        break;

    when used inside one nested loop.
*/

for ($outer = 1; $outer <= 5; $outer++) {

    echo "Outer: " . $outer . "<br>";

    $inner = 1;

    while ($inner <= 5) {

        if ($inner == 3) {
            break 1;
        }

        echo "Inner: " . $inner . "<br>";

        $inner++;
    }
}

echo "<hr>";



/* =========================================================
   4. BREAK 2
   ========================================================= */

/*
    break 2 terminates two levels of loops.

    In this example there are three levels:

        Level 1: Outer for
        Level 2: Middle for
        Level 3: Inner for

    break 2 starts from the current loop and exits:

        Inner loop
        Middle loop

    The outer loop continues.
*/

for ($letter = "A"; $letter <= "D"; $letter++) {

    echo "Letter: " . $letter . "<br>";

    for ($number = 1; $number <= 3; $number++) {

        echo "Number: " . $number . "<br>";

        for ($value = 5; $value <= 7; $value++) {

            if ($value == 7) {
                break 2;
            }

            echo "Value: " . $value . "<br>";
        }
    }

    echo "<hr>";
}



/* =========================================================
   5. BREAK 3
   ========================================================= */

/*
    break 3 exits three nested loop levels.

    Starting from the current loop:

        Level 1 -> current inner loop
        Level 2 -> middle loop
        Level 3 -> outer loop

    Therefore, when the condition becomes true,
    all three loops are terminated.
*/

for ($letter = "A"; $letter <= "D"; $letter++) {

    echo "Letter: " . $letter . "<br>";

    for ($number = 1; $number <= 3; $number++) {

        echo "Number: " . $number . "<br>";

        for ($value = 5; $value <= 7; $value++) {

            if ($value == 7) {
                break 3;
            }

            echo "Value: " . $value . "<br>";
        }
    }
}

echo "<hr>";



/* =========================================================
   6. BREAK 4
   ========================================================= */

/*
    break can specify the number of nested structures
    that should be terminated.

    Here there are four nested loops.

        Level 1 -> outer loop
        Level 2 -> second loop
        Level 3 -> third loop
        Level 4 -> innermost loop

    break 4 exits all four levels.
*/

for ($letter = "A"; $letter <= "D"; $letter++) {

    echo "Letter: " . $letter . "<br>";

    for ($number = 1; $number <= 3; $number++) {

        echo "Number: " . $number . "<br>";

        for ($value = 5; $value <= 7; $value++) {

            echo "Value: " . $value . "<br>";

            for ($smallLetter = "a"; $smallLetter <= "f"; $smallLetter++) {

                if ($smallLetter == "d") {
                    break 4;
                }

                echo "Small Letter: " . $smallLetter . "<br>";
            }
        }
    }
}

echo "<hr>";



/* =========================================================
   7. BREAK FROM THE OUTER LOOP
   ========================================================= */

/*
    break can also be placed directly inside the outer loop.

    In this example, when the outer value reaches C,
    the complete outer loop is terminated.

    This means none of the inner loops are executed for C
    or any value after C.
*/

for ($letter = "A"; $letter <= "D"; $letter++) {

    echo "Letter: " . $letter . "<br>";

    if ($letter == "C") {
        break;
    }

    for ($number = 1; $number <= 3; $number++) {

        echo "Number: " . $number . "<br>";

        for ($value = 5; $value <= 7; $value++) {

            echo "Value: " . $value . "<br>";

            for ($smallLetter = "a"; $smallLetter <= "f"; $smallLetter++) {

                echo "Small Letter: " . $smallLetter . "<br>";
            }
        }
    }

    echo "<hr>";
}



/* =========================================================
   8. CONTINUE
   ========================================================= */

/*
    continue does NOT terminate the loop.

    Instead, it skips the remaining code of the current
    iteration and moves to the next iteration.
*/

for ($number = 1; $number <= 10; $number++) {

    if ($number == 5) {
        continue;
    }

    echo $number . " ";
}

echo "<hr>";



/* =========================================================
   9. CONTINUE INSIDE A WHILE LOOP
   ========================================================= */

/*
    continue can also be used inside a while loop.

    Important:

    The variable must still be updated correctly.

    Otherwise, continue can prevent the loop condition
    from ever changing and create an infinite loop.
*/

$number = 1;

while ($number <= 10) {

    $number++;

    if ($number == 5) {
        continue;
    }

    echo $number . " ";
}

echo "<hr>";



/* =========================================================
   10. CONTINUE WITH MULTIPLE CONDITIONS
   ========================================================= */

/*
    More than one value can be skipped.

    Here both 2 and 3 are skipped.
*/

for ($letter = "a"; $letter <= "h"; $letter++) {

    echo "Letter: " . $letter . "<br>";

    $number = 1;
    $value = 5;

    for ($number; $number <= 3; $number++) {

        if ($number == 2 || $number == 3) {
            continue;
        }

        echo "Number: " . $number;
        echo " | Value: " . $value;
        echo "<br>";

        $value--;
    }
}

echo "<hr>";



/* =========================================================
   11. CONTINUE WITH NESTED LOOPS
   ========================================================= */

/*
    By default, continue affects only the current loop.

    Therefore, when continue is inside the innermost loop,
    the outer loops continue normally.
*/

for ($number = 1; $number <= 3; $number++) {

    for ($letter = "A"; $letter <= "D"; $letter++) {

        for ($value = 5; $value <= 7; $value++) {

            if ($value == 6) {
                continue;
            }

            echo $number . " " . $letter . " " . $value;
            echo "<br>";
        }
    }

    echo "<hr>";
}



/* =========================================================
   12. CONTINUE WITH A LOOP LEVEL
   ========================================================= */

/*
    continue can specify a loop level.

    continue 3 means:

        Skip the current iteration of the loop at the
        specified nesting level and continue with the
        next iteration of that outer loop.

    This is different from break 3.

        break 3
            -> completely terminates three loop levels.

        continue 3
            -> skips to the next iteration of the third
               loop level.
*/

for ($number = 1; $number <= 3; $number++) {

    for ($letter = "A"; $letter <= "D"; $letter++) {

        for ($value = 5; $value <= 7; $value++) {

            if ($value == 6) {
                continue 3;
            }

            echo $number . " " . $letter . " " . $value;
            echo "<br>";
        }
    }

    echo "<hr>";
}



/* =========================================================
   13. MULTIPLE VARIABLES IN FOR
   ========================================================= */

/*
    A for loop can initialize more than one variable.

    Example:

        $a starts at 1
        $b starts at 5

    Both variables can also be updated in the third section.
*/

for ($a = 1, $b = 5; $a <= $b; $a++) {

    echo $a . " ";
}

echo "<hr>";



/* =========================================================
   14. MULTIPLE VARIABLES WITH DIFFERENT UPDATES
   ========================================================= */

/*
    Here:

        $a increases
        $b decreases

    on every iteration.
*/

for ($a = 1, $b = 5; $a <= $b; $a++, $b--) {

    echo "A: " . $a . " | B: " . $b;
    echo "<br>";
}

echo "<hr>";



/* =========================================================
   15. LOOP CONDITION USING LOGICAL OPERATORS
   ========================================================= */

/*
    A for loop condition can contain expressions.

    TRUE XOR FALSE = TRUE

    Therefore, this condition remains true.

    This example is intentionally limited with break so that
    it does not become an infinite loop.
*/

for ($number = 1; true xor false; $number++) {

    echo $number . " ";

    if ($number == 5) {
        break;
    }
}

echo "<hr>";



/* =========================================================
   16. LOOP CONDITION USING NULL
   ========================================================= */

/*
    NULL evaluates as false in a boolean context.

    Therefore:

        !(NULL && false)

    becomes true.

    Again, the loop is intentionally stopped with break.
*/

for ($number = 1; !(NULL && false); $number++) {

    echo $number . " ";

    if ($number == 5) {
        break;
    }
}

echo "<hr>";



/* =========================================================
   17. NESTED LOOP WITH DIFFERENT LOOP TYPES
   ========================================================= */

/*
    PHP allows different loop types to be nested together.

    Example:

        while
            -> for
                -> while
*/

$letter = "A";

while ($letter <= "D") {

    echo "Outer Letter: " . $letter . "<br>";

    for ($number = 1; $number <= 3; $number++) {

        echo "Number: " . $number . "<br>";

        $smallLetter = "a";

        while ($smallLetter <= "d") {

            if ($smallLetter == "b" || $smallLetter === "d") {
                break;
            }

            echo "Inner Letter: " . $smallLetter . "<br>";

            $smallLetter++;
        }
    }

    $letter++;
}

echo "<hr>";



/* =========================================================
   18. BREAK VS CONTINUE
   ========================================================= */

/*
    The most important difference:

    break
        -> Stops the loop completely.

    continue
        -> Skips only the current iteration.

    Example with break:
*/

for ($number = 1; $number <= 10; $number++) {

    if ($number == 5) {
        break;
    }

    echo $number . " ";
}

echo "<br>";



/*
    Example with continue:
*/

for ($number = 1; $number <= 10; $number++) {

    if ($number == 5) {
        continue;
    }

    echo $number . " ";
}


/*
    BREAK OUTPUT:

        1 2 3 4

    CONTINUE OUTPUT:

        1 2 3 4 6 7 8 9 10
*/


/*
    SUMMARY

    break:
        Stops execution of the selected loop level.

    break 2:
        Stops two nested loop levels.

    break 3:
        Stops three nested loop levels.

    break 4:
        Stops four nested loop levels.

    continue:
        Skips the current iteration.

    continue 3:
        Skips to the next iteration of the specified
        outer loop level.

    Important:

    Level numbers should be used carefully because they
    depend on the current nesting depth.
*/

?>