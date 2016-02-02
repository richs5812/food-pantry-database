<?php

session_start();
if(empty($_SESSION['username'])) {
header('Location: login.php');
exit;
}else {
	echo '<p class="sessionText">Logged in as: ' . $_SESSION["username"] . ' <a href="logout.php">Logout</a></p>';
}

?>