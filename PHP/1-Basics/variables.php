<?php
    /*
    Variables: Variables are used to store data.

    A variable is like a container that holds a value.
    The value stored inside a variable can be changed later.

    -> Variable Naming Rules

    Valid examples:
        $name
        $_name
        $name123
        $customerName
        $customer_name
        $_9876

    Invalid examples:
        $9name
        $&name
        $0

    Rules:
        -> A variable must start with $.
        -> The first character after $ must be a letter or underscore.
        -> A variable cannot start with a number.
        -> A variable cannot contain special characters except underscore.
        -> Variable names are case-sensitive.

    Example:
        $name and $NAME are two different variables.
    */


    // Creating Variables

    $name = "Sudheer";
    $age = 22;
    $city = "Shikarpur";

    echo $name;
    echo "<br>";

    echo $age;
    echo "<br>";

    echo $city;
    echo "<br><br>";


    // Variables are Case-Sensitive

    /*
    PHP treats uppercase and lowercase variable names as different.

    $name and $NAME are not the same variable.
    */

    $name = "Sudheer";
    $NAME = "PHP Developer";

    echo $name;
    echo "<br>";

    echo $NAME;
    echo "<br><br>";


    // Underscore in Variable Names

    $_name = "Sudheer";
    $_9876 = "PHP";

    echo $_name;
    echo "<br>";

    echo $_9876;
    echo "<br><br>";


    // Camel Case Naming

    $customerName = "Ali Khan";
    $customerDateOfBirth = "08-April-2004";

    echo $customerName;
    echo "<br>";

    echo $customerDateOfBirth;
    echo "<br><br>";


    // Snake Case Naming

    $customer_name = "Ahmed Khan";
    $customer_date_of_birth = "09-April-2004";

    echo $customer_name;
    echo "<br>";

    echo $customer_date_of_birth;
    echo "<br><br>";


    // Invalid Variable Names

    /*
    The following variable names are invalid.

    $9name
    $&name
    $0
    $9876

    They are kept inside comments because using them as actual PHP
    variables would cause syntax errors.
    */


    // Variable Assignment

    /*
    A variable can be assigned a value.

    The same variable can later receive another value.
    When this happens, the old value is replaced.
    */

    $data = "Sudheer";
    echo $data;
    echo "<br>";

    $data = 1098;
    echo $data;
    echo "<br>";

    $data = 8.9;
    echo $data;
    echo "<br>";

    $data = "PHP Developer";
    echo $data;
    echo "<br>";

    $data = null;
    var_dump($data);
    echo "<br>";

    $data = "";
    echo $data;
    echo "<br>";

    $data = true;
    echo $data;
    echo "<br>";

    $data = false;
    echo $data;
    echo "<br><br>";


    // Reassigning a Variable

    /*
    A variable does not have to keep the same value.

    Every new assignment replaces the previous value.
    */

    $data = "PHP Basic";
    $data = "PHP Intermediate";
    $data = "PHP Advanced";

    echo $data;
    echo "<br><br>";


    // Variable Without an Initial Value

    /*
    A variable can be declared without immediately assigning
    a value.

    It is considered undefined until a value is assigned.
    */

    $ourData;

    // Assigning different types of values

    $ourData = "PHP";
    echo $ourData;
    echo "<br>";

    $ourData = 0;
    echo $ourData;
    echo "<br>";

    $ourData = null;
    var_dump($ourData);
    echo "<br>";

    $ourData = "";
    echo $ourData;
    echo "<br>";

    $ourData = " ";
    echo $ourData;
    echo "<br>";

    $ourData = false;
    var_dump($ourData);
    echo "<br><br>";


    // Combining Variables

    $firstName = "Sudheer";
    $lastName = "Mangi";
    $score = 280;

    echo $firstName . " " . $lastName;
    echo "<br>";

    echo $firstName . " " . $lastName . " scored " . $score . " points.";
    echo "<br><br>";


    // Variable Naming Styles

    /*
    Common naming styles include:

        camelCase
        snake_case

    Examples:
        $customerName
        $customer_name
    */

    $studentName = "Sudheer";
    $student_name = "Sudheer";

    echo $studentName;
    echo "<br>";

    echo $student_name;
?>