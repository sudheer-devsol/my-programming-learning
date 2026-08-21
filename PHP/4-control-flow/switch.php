<?php
    /*
    SWITCH Statement

    switch is useful when one value needs to be compared
    against multiple possible values.

    Syntax:

        switch ($value) {
            case value:
                // code
                break;

            default:
                // code
        }

    break stops PHP from continuing into the next case.
    */


    // Basic Switch

    $number = 3;

    switch ($number) {

        case 1:
            echo "It is one.";
            break;

        case 2:
            echo "It is two.";
            break;

        case 3:
            echo "It is three.";
            break;

        default:
            echo "No match.";
    }

    echo "<br><br>";


    // Default Case

    $number = 10;

    switch ($number) {

        case 1:
            echo "It is one.";
            break;

        case 2:
            echo "It is two.";
            break;

        default:
            echo "The value did not match any case.";
    }

    echo "<br><br>";


    // Multiple Values for One Case

    /*
    Multiple cases can point to the same block.

    This is useful when different values should produce
    the same result.
    */

    $day = "Friday";

    switch ($day) {

        case "Monday":
        case 1:
            echo "Today is Monday.";
            break;

        case "Tuesday":
        case 2:
            echo "Today is Tuesday.";
            break;

        case "Wednesday":
        case 3:
            echo "Today is Wednesday.";
            break;

        case "Thursday":
        case 4:
            echo "Today is Thursday.";
            break;

        case "Friday":
        case 5:
            echo "Today is Friday.";
            break;

        case "Saturday":
        case 6:
            echo "Today is Saturday.";
            break;

        case "Sunday":
        case 7:
            echo "Today is Sunday.";
            break;

        default:
            echo "Day not matched.";
    }

    echo "<br><br>";


    // switch(true)

    /*
    switch(true) allows us to use conditions in cases.

    The first case whose condition evaluates to true
    will execute.
    */

    $salary = 45000;

    switch (true) {

        case ($salary >= 60000):
            echo "Bonus: 20%";
            break;

        case ($salary >= 50000):
            echo "Bonus: 10%";
            break;

        case ($salary >= 40000):
            echo "Bonus: 5%";
            break;

        case ($salary >= 30000):
            echo "Bonus: 2%";
            break;

        default:
            echo "No bonus.";
    }

    echo "<br><br>";


    // Switch with Grades

    $marks = 85;

    switch (true) {

        case ($marks >= 90):
            echo "Grade A+";
            break;

        case ($marks >= 80):
            echo "Grade A";
            break;

        case ($marks >= 70):
            echo "Grade B";
            break;

        case ($marks >= 60):
            echo "Grade C";
            break;

        case ($marks >= 50):
            echo "Pass";
            break;

        default:
            echo "Failed.";
    }


    /*
    Important:

    In a normal switch, forgetting break can cause PHP to
    continue executing the following cases.

    This behavior is called fall-through.

    Example:

        case 1:
            echo "One";

        case 2:
            echo "Two";
            break;

    If the value is 1, both "One" and "Two" can be printed.
    */
?>