<?php
require_once ('mysql_connect.php');
//create array from form input
$posts = array($_POST['username']);

//create array to place either post value or NULL
$fieldArray = array();

//loop through each post value and assign NULL value if no input
$arrlength = count($posts);
for($x = 0; $x < $arrlength; $x++) {
    $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
}

$stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");

$stmt->bind_param('s', $fieldArray[0]);

if ($stmt->execute() == TRUE) {

$result = $stmt->get_result();

$hash = "";

while ($row = $result->fetch_array(MYSQLI_ASSOC)){
	$hash = $row['password'];
}

//echo $hash;

if (password_verify($_POST['password'], $hash)) {
    //echo 'Password is valid!';
    session_start();
        //take user to database if username/password combo found
        $_SESSION["username"] = $_POST[username];
        header('Location: brightmoorPantry.php');
		exit;
} else {
    echo 'Invalid password.';
}
/*
if ($result->num_rows > 0) {

        session_start();
        //take user to database if username/password combo found
        $_SESSION["username"] = $_POST[username];
        header('Location: brightmoorPantry.php');
		exit;
/*
        while ($row = $result->fetch_array(MYSQLI_NUM))
        {
        
            foreach ($row as $r)
            {
                print "$r ";
            }
            print "\n";
        }
        
} else {
	header('Location: brightmoorPantry.php');
	echo 'No results <br />';
	}

  //echo '<a href="login.php">Return to login form</a><br /><a href="brightmoorPantry.php">Go to database</a>';
*/
}
else {
	echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
	echo "<br><br> <a href=\"login.php\">Return to login form</a>";
}

$conn->close();

?>