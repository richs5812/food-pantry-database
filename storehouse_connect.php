<?php

$servername = "localhost";


//prod setup
$username = "StorehouseUser";
$password = "jRGb8xtUykwdEzKa75McZD7y";
$dbname = "Storehouse";

/*
//brightmoor QA setup
$username = "BrightmoorQA";
$password = "2>3bmUqxXDcU?R-#";
$dbname = "QABrightmoorConnectQA";
*/
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (!$conn)
  {
  die("Connection error: " . mysqli_connect_error());
  }

?>
