<?php
    /*
    Logical Operators: Logical operators are used to combine
    or reverse conditions.

    PHP supports:

        !       NOT
        &&      AND
        and     AND
        ||      OR
        or      OR
        xor     Exclusive OR

    Logical expressions return true or false.
    */


    // NOT Operator !

    var_dump(!true);
    echo "<br>";

    var_dump(!false);
    echo "<br>";

    var_dump(!!!true);
    echo "<br><br>";


    // AND Operator &&

    var_dump(true && false);
    echo "<br>";

    var_dump(false && false);
    echo "<br>";

    var_dump(true && true);
    echo "<br><br>";


    // OR Operator ||

    var_dump(true || false);
    echo "<br>";

    var_dump(false || true);
    echo "<br>";

    var_dump(false || false);
    echo "<br><br>";


    // XOR Operator

    /*
    XOR returns true when exactly one of the two conditions
    is true.

        true XOR false = true
        false XOR true = true
        true XOR true = false
        false XOR false = false
    */

    var_dump(true xor false);
    echo "<br>";

    var_dump(false xor false);
    echo "<br>";

    var_dump(true xor true);
    echo "<br>";

    var_dump(false xor true);
    echo "<br><br>";


    // Logical Operators with Values

    /*
    PHP evaluates values according to their truthiness.

    Examples of values that are considered false include:

        false
        0
        ""
        "0"
        null
        empty arrays

    Most other values are considered true.
    */

    var_dump("PHP" && 10);
    echo "<br>";

    var_dump(null && false);
    echo "<br>";

    var_dump("PHP" || 0);
    echo "<br>";

    var_dump(0 || 1);
    echo "<br><br>";


    // Using Parentheses

    /*
    Parentheses make the intended order of evaluation clear.

    This is especially important when mixing multiple
    logical operators.
    */

    $result =
        (!("PHP Basic") || 1)
        && (1 < 5 || 6 !== "6");

    var_dump($result);
    echo "<br><br>";


    // and / && Difference

    /*
    PHP has both:

        &&
        and

    They both represent logical AND, but they have different
    operator precedence.

    && has higher precedence than "and".

    This means parentheses are recommended when expressions
    become complicated.
    */

    $result = true && false;

    var_dump($result);
    echo "<br>";

    $result = true and false;

    var_dump($result);
    echo "<br><br>";


    /*
    The previous example demonstrates why "and" and "&&"
    should not be mixed carelessly with assignment.

    For example:

        $result = true and false;

    is interpreted effectively as:

        ($result = true) and false;

    So $result becomes true.
    */


    // or / ||

    $result = false || true;

    var_dump($result);
    echo "<br>";

    $result = false or true;

    var_dump($result);
    echo "<br><br>";


    // Complex Logical Expression

    $number = 5;

    $result =
        (($number == "5" && --$number === 4)
        xor ($number != "5"));

    var_dump($result);
?>