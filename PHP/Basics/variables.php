<?php
    /* Variables: Variables are used to store data. it's like a container to put something, now that 
    something will be the value 
    
    -> Variable Naming Rules
    valid way to define variables
    * $name
    * $_name
    * $name123
    * $customerName
    * $customer_name
    * $_9876
    
    Invalid way to define variables
    * $9name
    * $&name
    * $0

    Rules:
        Must start with $.
        First character after $ must be a letter or _.
        Cannot start with a number.
        Cannot contain special characters except _.

    Note: In PHP Variable names are case-sensitive.
    $name = "Ali";
    $NAME = "Ahmed";
    Both are different
    
    */
    // Variable Assignment
    // $data = "PHP";
    // $data = 100;
    // $data = 45.5;
    // $data = true;
    // $data = null;
    // Note: here all time the variable $data will be overrided with new value
    
    
    //Data Types
    //PHP supports several data types.
    $name = "Ali";      // String
    $age = 20;          // Integer
    $marks = 88.5;      // Float
    $isPass = true;     // Boolean
    $data = null;       // NULL
    
    // echo $name;
    // echo $age;
    // echo $marks;
    // echo $isPass;
    // echo $data;

    echo "<br>";
    
    // We can print all in one shot as 
    echo "The Student $name whose age is $age he scored $marks% in exams.";
?>