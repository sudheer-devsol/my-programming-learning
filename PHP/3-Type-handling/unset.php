<?php
    /*
    unset()

    unset() removes a variable.

    After unset() is called, the variable no longer exists.

    Syntax:

        unset($variable);
    */


    // Creating a Variable

    $data = "PHP Basic";

    echo $data;
    echo "<br><br>";


    // Removing the Variable

    unset($data);


    /*
    The variable has now been removed.

    Trying to directly access it after unset() will result
    in an undefined variable warning.

    Instead, we can safely check it using isset().
    */

    var_dump(isset($data));
    echo "<br><br>";


    // Creating the Variable Again

    $data = "PHP Advanced";

    echo $data;
    echo "<br><br>";


    // Removing the Variable Again

    unset($data);

    var_dump(isset($data));
    echo "<br><br>";


    // unset() with Multiple Variables

    $firstName = "Sudheer";
    $lastName = "Mangi";

    echo $firstName . " " . $lastName;
    echo "<br><br>";

    unset($firstName, $lastName);

    var_dump(isset($firstName));
    echo "<br>";

    var_dump(isset($lastName));
    echo "<br><br>";


    // unset() Does Not Set a Variable to NULL

    /*
    There is an important difference:

        $data = null;

    means the variable still exists but contains NULL.

    While:

        unset($data);

    removes the variable completely.
    */

    $data = null;

    echo "After assigning NULL: ";
    var_dump(isset($data));

    echo "<br>";

    $data = "PHP";

    unset($data);

    echo "After unset(): ";
    var_dump(isset($data));
?>