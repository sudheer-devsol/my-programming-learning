<?php

/*
    GOTO STATEMENT

    The goto statement allows PHP to jump directly to a
    labeled section of code.

    Syntax:

        goto label;

        label:
            // code

    The label is followed by a colon (:).

    Example:

        goto end;

        echo "This will be skipped.";

        end:
        echo "Execution continued here.";

    IMPORTANT:

    goto should be used carefully.

    Although PHP supports goto, excessive use can make
    code difficult to read and maintain.

    In normal application development, structured control
    flow such as:

        if / else
        loops
        functions
        switch / match

    is usually easier to understand.

    These examples are included here to understand how
    PHP's control flow actually works.
*/


/* =========================================================
   1. BASIC GOTO
   ========================================================= */

/*
    goto jumps directly to the specified label.

    Therefore, the statement before the label is skipped.
*/

goto end;

echo "This statement will be skipped.";

end:

echo "Execution reached the end label.";

echo "<hr>";



/* =========================================================
   2. GOTO CAN SKIP MULTIPLE STATEMENTS
   ========================================================= */

goto section_two;

echo "Statement 1";
echo "Statement 2";
echo "Statement 3";

section_two:

echo "Execution continued from section two.";

echo "<hr>";



/* =========================================================
   3. GOTO WITH MULTIPLE LABELS
   ========================================================= */

/*
    A file can contain multiple labels.

    goto can jump to any valid label in the same scope.
*/

goto fourth;

first:

echo "First section";
echo "<br>";

second:

echo "Second section";
echo "<br>";

third:

echo "Third section";
echo "<br>";

fourth:

echo "Fourth section";
echo "<br>";

echo "<hr>";



/* =========================================================
   4. GOTO CAN SKIP AN ENTIRE SECTION
   ========================================================= */

goto final_section;

first_section:

echo "This is the first section.";
echo "<br>";

second_section:

echo "This is the second section.";
echo "<br>";

third_section:

echo "This is the third section.";
echo "<br>";

final_section:

echo "The previous sections were skipped.";

echo "<hr>";



/* =========================================================
   5. GOTO AFTER A STATEMENT
   ========================================================= */

/*
    goto does not automatically stop the entire script.

    It only changes the next location from which execution
    continues.
*/

echo "Before goto.";
echo "<br>";

goto continue_here;

echo "This will be skipped.";

continue_here:

echo "Execution continued here.";

echo "<hr>";



/* =========================================================
   6. GOTO AND LOOPS
   ========================================================= */

/*
    goto can jump out of a loop to a label located outside
    the loop.

    This can be used to terminate a process, although
    break is generally clearer when working with loops.
*/

for ($letter = "a"; $letter <= "g"; $letter++) {

    if ($letter == "d") {
        goto outside_loop;
    }

    echo "Letter: " . $letter;
    echo "<br>";
}

outside_loop:

echo "Execution continued outside the loop.";

echo "<hr>";



/* =========================================================
   7. GOTO FROM A NESTED LOOP
   ========================================================= */

/*
    goto can jump out of multiple nested loops.

    Unlike break 2 or break 3, goto jumps directly to
    the specified label.
*/

for ($letter = "A"; $letter <= "C"; $letter++) {

    echo "Outer: " . $letter;
    echo "<br>";

    $number = 10;

    while ($number >= 1) {

        echo "Inner: " . $number;
        echo "<br>";

        if ($number == 5) {
            goto nested_loop_end;
        }

        $number--;
    }
}

nested_loop_end:

echo "Nested loop ended.";

echo "<hr>";



/* =========================================================
   8. GOTO FROM A DEEPER NESTED LOOP
   ========================================================= */

/*
    Here we have three levels:

        for
            while
                do-while

    goto can jump directly from the innermost level to
    a label outside all three structures.
*/

for ($letter = "A"; $letter <= "B"; $letter++) {

    $number = 10;

    while ($number >= 1) {

        if ($number == 6) {

            $value = 5;

            do {

                echo "Do-while value: " . $value;
                echo "<br>";

                if ($value == 6) {
                    goto deep_loop_end;
                }

                $value++;

            } while ($value <= 7);
        }

        $number--;
    }
}

deep_loop_end:

echo "Deep nested loop ended.";

echo "<hr>";



/* =========================================================
   9. GOTO WITH DO-WHILE
   ========================================================= */

/*
    goto can also be used inside a do-while loop.

    The label can be outside the loop.
*/

$number = 1;

do {

    echo "Number: " . $number;
    echo "<br>";

    if ($number == 4) {
        goto do_while_end;
    }

    $number++;

} while ($number <= 10);

do_while_end:

echo "Do-while loop ended.";

echo "<hr>";



/* =========================================================
   10. GOTO CAN JUMP FORWARD
   ========================================================= */

/*
    The most common demonstration of goto is a forward jump.

    The statements between goto and the label are skipped.
*/

goto destination;

echo "Skipped statement A.";
echo "<br>";

echo "Skipped statement B.";
echo "<br>";

echo "Skipped statement C.";
echo "<br>";

destination:

echo "Reached destination.";

echo "<hr>";



/* =========================================================
   11. GOTO CAN JUMP BACKWARD
   ========================================================= */

/*
    A goto can also jump to a label that appears earlier
    in the code.

    However, backward jumps can easily create an infinite
    loop if there is no condition that eventually stops it.

    This example uses a counter to control the execution.
*/

$count = 1;

start:

echo "Count: " . $count;
echo "<br>";

$count++;

if ($count <= 3) {
    goto start;
}

echo "Backward jump completed.";

echo "<hr>";



/* =========================================================
   12. GOTO WITH A CONDITION
   ========================================================= */

/*
    goto itself does not make a decision.

    A condition can decide whether the program should jump.
*/

$number = 1;

if ($number == 1) {
    goto valid;
}

echo "Invalid value.";

valid:

echo "Valid value.";

echo "<hr>";



/* =========================================================
   13. GOTO WITH MULTIPLE CONDITIONS
   ========================================================= */

$number = 10;

if ($number >= 10) {

    goto approved;

}

echo "The value does not meet the requirement.";

approved:

echo "The value meets the requirement.";

echo "<hr>";



/* =========================================================
   14. GOTO AND LOOP TERMINATION
   ========================================================= */

/*
    A loop can be terminated using goto.

    However, when the only purpose is to leave a loop,
    break is normally easier to understand.

    This example is kept to demonstrate the behavior.
*/

$number = 1;

while ($number <= 10) {

    echo $number . " ";

    if ($number == 5) {
        goto loop_finished;
    }

    $number++;
}

loop_finished:

echo "<br>";
echo "Loop finished.";

echo "<hr>";



/* =========================================================
   15. GOTO VS BREAK
   ========================================================= */

/*
    BREAK

        break;
            -> exits the current loop.

        break 2;
            -> exits two nested loop levels.

    GOTO

        goto label;
            -> jumps directly to a specified label.

    Example using break:
*/

for ($number = 1; $number <= 10; $number++) {

    if ($number == 5) {
        break;
    }

    echo $number . " ";
}

echo "<br>";



/*
    Example using goto:
*/

for ($number = 1; $number <= 10; $number++) {

    if ($number == 5) {
        goto loop_end;
    }

    echo $number . " ";
}

loop_end:

echo "<hr>";



/* =========================================================
   16. GOTO WITH DO-WHILE AND CONDITION
   ========================================================= */

/*
    This example combines:

        do-while
        condition
        increment
        goto

    It demonstrates how goto can redirect execution from
    inside a loop.
*/

$number = 1;

do {

    echo "Processing: " . $number;
    echo "<br>";

    $number++;

    if ($number == 5) {
        goto process_finished;
    }

} while ($number <= 10);

process_finished:

echo "Processing completed.";

echo "<hr>";



/*
    SUMMARY

    goto:

        goto label;

    label:
        // code

    Important concepts:

        1. goto jumps directly to a named label.

        2. A label ends with a colon (:).

        3. goto can skip statements.

        4. goto can jump forward.

        5. goto can jump backward.

        6. goto can jump out of loops.

        7. goto can jump out of nested loops.

        8. goto can be combined with conditions.

        9. Backward goto can create an infinite loop
           if it is not controlled.

       10. break is generally clearer when the goal is
           simply to exit a loop.

    The purpose of this file is to understand PHP's
    control-flow behavior, not to encourage excessive
    use of goto in application code.
*/

?>