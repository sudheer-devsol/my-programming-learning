<?php
    /*
    Spaceship Operator

    The spaceship operator is:

        <=>

    It compares two values and returns:

        -1   Left value is smaller
         0   Both values are equal
         1   Left value is greater

    Syntax:

        $first <=> $second;
    */


    // Equal Numbers

    echo 1 <=> 1;

    echo "<br>";

    // Result: 0


    // Left Value is Smaller

    echo 1 <=> 2;

    echo "<br>";

    // Result: -1


    // Left Value is Greater

    echo 2 <=> 1;

    echo "<br><br>";

    // Result: 1


    // Comparing Strings

    echo "a" <=> "a";

    echo "<br>";

    echo "a" <=> "b";

    echo "<br>";

    echo "b" <=> "a";

    echo "<br><br>";


    // Comparing Float Values

    echo 1.5 <=> 1.5;

    echo "<br>";

    echo 1.5 <=> 2.5;

    echo "<br>";

    echo 2.5 <=> 1.5;

    echo "<br><br>";


    // Using the Result in a Variable

    $number1 = 10;
    $number2 = 20;

    $result = $number1 <=> $number2;

    echo $result;
?>