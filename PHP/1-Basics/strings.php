<?php
    /*
    Strings: A string is a sequence of characters or text.

    Strings are used to store:

        -> Names
        -> Messages
        -> Addresses
        -> Emails
        -> Sentences
        -> Other text data

    PHP supports strings using:

        -> Single quotes ' '
        -> Double quotes " "
    */


    // Creating a String

    $name = "Sudheer";
    $city = "Shikarpur";
    $message = 'Welcome to PHP';

    echo $name;
    echo "<br>";

    echo $city;
    echo "<br>";

    echo $message;
    echo "<br><br>";


    // Single-Quoted Strings

    $name = 'Sudheer';

    echo $name;
    echo "<br><br>";


    // Double-Quoted Strings

    $name = "Sudheer";

    echo $name;
    echo "<br><br>";


    // Single Quotes vs Double Quotes

    /*
    Variables inside single quotes are treated as normal text.

    Variables inside double quotes can be interpreted by PHP.
    This is called variable interpolation.
    */

    $name = "Sudheer";

    echo 'My name is $name.';
    echo "<br>";

    echo "My name is $name.";
    echo "<br><br>";


    // Quotes Inside Strings

    /*
    We can use one type of quotation mark inside the other
    without escaping it.

    Example:
        Double quotes can contain single quotes.
        Single quotes can contain double quotes.
    */

    $message = "Today I am learning 'PHP'.";
    echo $message;
    echo "<br>";

    $message = 'Today I am learning "PHP".';
    echo $message;
    echo "<br><br>";


    // Escape Characters

    /*
    When the same quotation mark is needed inside a string,
    we can escape it using a backslash (\).

    Example:

        \"
        \'
    */

    $message = "Today I am learning \"PHP\".";

    echo $message;
    echo "<br>";

    $message = 'Today I am learning \'PHP\'.';

    echo $message;
    echo "<br><br>";


    // Invalid Quote Examples

    /*
    The following examples would cause syntax errors because
    the string is ended before the intended quotation mark.

        $data = "Today I am learning "PHP"";
        $data = 'Today I am learning 'PHP'';

    Escape characters solve this problem.
    */


    // String with an Apostrophe

    $message = "He Said \"That's\" Fine And Left";

    echo $message;
    echo "<br><br>";


    // String Concatenation

    /*
    Concatenation means joining multiple strings together.

    PHP uses the dot (.) operator for concatenation.
    */

    $firstName = "Sudheer";
    $middleName = "Ahmed";
    $lastName = "Mangi";

    echo $firstName . $middleName . $lastName;
    echo "<br>";

    echo $firstName . " " . $middleName . " " . $lastName;
    echo "<br><br>";


    // Concatenating Text and Variables

    $carGameScore = 280;

    echo $firstName . " " . $lastName .
        " scored " . $carGameScore . " points.";

    echo "<br><br>";


    // Concatenation Assignment Operator

    /*
    The .= operator adds new text to the existing string.

    Example:

        $message .= " More text";
    */

    $message = "Hello";

    $message .= " Sudheer";
    $message .= ", welcome to PHP.";

    echo $message;
    echo "<br><br>";


    // String Length

    /*
    strlen() returns the number of characters in a string.
    */

    $text = "Hello PHP";

    echo strlen($text);
    echo "<br><br>";


    // Convert String to Uppercase

    $text = "hello php";

    echo strtoupper($text);
    echo "<br><br>";


    // Convert String to Lowercase

    $text = "HELLO PHP";

    echo strtolower($text);
    echo "<br><br>";


    // Accessing Characters

    /*
    String characters use zero-based indexing.

        H = 0
        e = 1
        l = 2
        l = 3
        o = 4
    */

    $text = "Hello";

    echo $text[0];
    echo "<br>";

    echo $text[1];
    echo "<br>";

    echo $text[2];
    echo "<br><br>";


    // Updating a Character

    $text = "Hello";

    $text[0] = "J";

    echo $text;
    echo "<br><br>";


    // Checking a String's Data Type

    $name = "Sudheer";

    var_dump($name);
?>