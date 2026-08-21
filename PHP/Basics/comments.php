<?php
    /*
    Comments: Comments are used to write notes or explanations
    inside the code.

    Comments are ignored by PHP when the code is executed.

    Comments are useful for:
        -> Explaining the code
        -> Making code easier to understand
        -> Adding notes for yourself or other developers
        -> Temporarily disabling code

    PHP supports three common ways to write comments:

    1. Single-line comment using //
    2. Single-line comment using #
    3. Multi-line comment using  /* */


    // This is a single-line comment using two forward slashes.

    echo "Hello PHP";
    echo "<br>";


    # This is also a single-line comment.

    echo "Learning PHP";
    echo "<br>";


    /*
        This is a multi-line comment.

        We can write multiple lines
        inside this type of comment.
    */

    echo "PHP Comments";
    echo "<br>";


    // Comments can also be used to temporarily disable code.

    $name = "Ali";

    // echo $name;

    echo "The name is $name.";
    echo "<br>";


    /*
        The following code will not be executed
        because it is inside a comment.

        echo "This will not be displayed.";
    */
?>