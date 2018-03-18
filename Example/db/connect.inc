<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);
//echo "<b>Results from Login.php</b><br><br>";
//echo "<b>Results from Login.php</b><br><br>";


$path = $_SERVER['DOCUMENT_ROOT'] . '/Midterm/apache/Example/db/config.php';
require_once($path);

function connectToDb()
{
/*SQL connection*/
global $conn;
$conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);


/*Checking Connection*/
if (mysqli_connect_errno()) {
     printf("Connect failed: %s\n", mysqli_connect_error());
     exit();
}
else{
     //echo "connection was successful to database";
}

ini_set('display_errors', 1);//Error checking
return $conn;
}
?>
