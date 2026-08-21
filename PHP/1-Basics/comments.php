<?php
    /*
    Comments: Comments are notes written inside source code.

    PHP ignores comments during execution.

    Comments are useful for:

        -> Explaining code
        -> Writing notes
        -> Documenting concepts
        -> Temporarily disabling code
        -> Making learning examples easier to understand
    */


    // Single-Line Comment

    // echo "This code is commented out.";

    echo "Single-line comments use //.";
    echo "<br><br>";


    # Single-Line Comment using #

    # echo "This code is also commented out.";

    echo "PHP also supports # for single-line comments.";
    echo "<br><br>";


    /*
        Multi-Line Comment

        Multiple lines can be written
        inside a single comment block.
    */

    echo "Multi-line comments use /* and */.";
    echo "<br><br>";


    // Comments can be used to temporarily disable code

    $name = "Sudheer";

    // echo $name;

    echo "The variable contains: " . $name;
    echo "<br><br>";


    /*
        The following code is disabled.

        $name = "Ali";
        echo $name;

        PHP will completely ignore this section.
    */


    // Comments can also explain why code exists

    $score = 90;

    // Display the student's score.

    echo "Student Score: " . $score;
?>