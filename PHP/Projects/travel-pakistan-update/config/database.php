<?php

mysqli_report(MYSQLI_REPORT_OFF);
// =====================Database Connection===============================

$host = "localhost";
$username = "root";
$u_password = "";
$database = "online_blogging_application";

// ==================Create Connection==================================

$conn = mysqli_connect($host, $username, $u_password, $database);

// ==============Check Connection======================================

// var_dump($conn);
// die();

if(mysqli_connect_errno()){

	echo "<p style='color:red'> Databse Connections Failed....! </p>";
	echo "<p style='color:red'> ERROR No: ".mysqli_connect_errno()."</p>";
	echo "<p style='color:red'> ERROR Message: ".mysqli_connect_error()."</p>";
}



?>