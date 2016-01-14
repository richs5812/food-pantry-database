<?php
require_once ('mysql_connect.php');
//create array from form input


$hashed_password = crypt('mypassword'); // let the salt be automatically generated


$stmt = $conn->prepare("INSERT INTO users (password) VALUES (?)");
$stmt->bind_param('s', $hashed_password);

if ($stmt->execute() == TRUE) {

echo 'new password created';
}

$conn->close();

?>