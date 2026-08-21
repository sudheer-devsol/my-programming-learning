<?php
    /*
    Strings: A string is a sequence of characters or text.

    Strings are commonly used to store:
        -> Names
        -> Messages
        -> Addresses
        -> Emails
        -> Sentences
        -> Any other text

    In PHP, strings can be written using:
        -> Single quotes ' '
        -> Double quotes " "

    Example:
    $name = "Ali";
    $city = 'Shikarpur';

    Note: Strings can contain letters, numbers, spaces and
    special characters.
    */


    // Creating Strings

    $name = "Ali";
    $city = "Shikarpur";
    $message = 'Welcome to PHP Learning';


    // Printing Strings

    echo $name;
    echo "<br>";

    echo $city;
    echo "<br>";

    echo $message;
    echo "<br><br>";


    // Single Quotes

    /*
    Strings written inside single quotes are treated as plain text.

    Example:
    */

    $name = 'Ali';

    echo $name;
    echo "<br><br>";


    // Double Quotes

    /*
    Double quotes allow us to use variables directly inside
    the string. This is called variable interpolation.
    */

    $name = "Ali";
    $age = 20;

    echo "My name is $name and I am $age years old.";
    echo "<br><br>";


    // Single Quotes vs Double Quotes

    /*
    In single quotes, the variable is not automatically replaced
    with its value.

    In double quotes, the variable is replaced with its value.
    */

    echo 'My name is $name.';
    echo "<br>";

    echo "My name is $name.";
    echo "<br><br>";


    // Concatenation

    /*
    Concatenation means joining two or more strings together.

    In PHP, we use the dot (.) operator for concatenation.

    Example:
    $firstName . $lastName
    */

    $firstName = "Ali";
    $lastName = "Khan";

    $fullName = $firstName . " " . $lastName;

    echo $fullName;
    echo "<br><br>";


    // Concatenating Multiple Strings

    $name = "Ali";
    $course = "PHP";
    $institute = "HIST";

    echo "My name is " . $name . " and I am learning "
        . $course . " from " . $institute . ".";

    echo "<br><br>";


    // Concatenation with Variables and Text

    $product = "Laptop";
    $price = 75000;

    echo "The price of the " . $product . " is Rs. " . $price . ".";

    echo "<br><br>";


    // Concatenation Assignment Operator

    /*
    We can also use .= to add more text to an existing string.

    Example:
    $message .= "More text";
    */

    $message = "Hello";

    $message .= " Ali";
    $message .= ", welcome to PHP.";

    echo $message;
    echo "<br><br>";


    // String Length

    /*
    strlen() is used to find the number of characters
    in a string.
    */

    $text = "Hello PHP";

    echo strlen($text);

    echo "<br><br>";


    // Converting String to Uppercase

    /*
    strtoupper() converts all characters of a string
    into uppercase letters.
    */

    $text = "hello php";

    echo strtoupper($text);

    echo "<br><br>";


    // Converting String to Lowercase

    /*
    strtolower() converts all characters of a string
    into lowercase letters.
    */

    $text = "HELLO PHP";

    echo strtolower($text);

    echo "<br><br>";


    // Accessing Characters of a String

    /*
    We can access individual characters of a string
    using their index number.

    The index starts from 0.

    Example:

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


    // Updating a Character in a String

    $text = "Hello";

    $text[0] = "J";

    echo $text;

    echo "<br><br>";


    // Checking the Data Type of a String

    /*
    var_dump() displays the data type and value.
    */

    $name = "Ali";

    var_dump($name);
?>