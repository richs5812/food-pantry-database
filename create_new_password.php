<?php
require_once ('mysql_connect.php');
//create array from form input


$hashed_password = password_hash("", PASSWORD_DEFAULT);


$stmt = $conn->prepare("INSERT INTO users (password) VALUES (?)");
$stmt->bind_param('s', $hashed_password);

if ($stmt->execute() == TRUE) {

echo 'new password created';
} else {
	echo 'error';
}

$conn->close();


/**
 * We just want to hash our password using the current DEFAULT algorithm.
 * This is presently BCRYPT, and will produce a 60 character result.
 *
 * Beware that DEFAULT may change over time, so you would want to prepare
 * By allowing your storage to expand past 60 characters (255 would be good)
 */
//echo password_hash("rasmuslerdorf", PASSWORD_DEFAULT)."\n";



?>