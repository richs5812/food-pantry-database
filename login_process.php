<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="db_styles.css">
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<title>Brightmoor Connection Database - login</title>
</head>
<body>

<header>
<img src="images/brightmoor_logo.jpg" width=500px>
<h1>Brightmoor Connection Database</h1>
</header>

<?php
require_once ('mysql_path.php');
require_once ($mysql_path);

//create array from form input
if(!isset($_POST['username'])){
	$_POST['username'] = NULL;
}

/* create a prepared statement */
if ($stmt = $conn->prepare("SELECT password FROM users WHERE username=?")) {

    /* bind parameters for markers */
    $stmt->bind_param("s", $_POST['username']);

    /* execute query */
    $stmt->execute();
    
    /* store result */
    $stmt->store_result();
    
    //get number of results
    $num_rows = $stmt->num_rows;
    
    /* bind result variables */
    $stmt->bind_result($password);

    /* fetch value */
    $stmt->fetch();

    /* close statement */
    $stmt->close();
}

$hash = $password;

//$result = $conn->query($passwordQuery);

if ( $num_rows > 0) {

		if(!isset($_POST['password'])){
			$_POST['password'] = NULL;
		}
		
		//php 5.6 - verify hash
		if (password_verify($_POST['password'], $hash)) {
		//php 5.4 - check vs raw password value
        //if ($hash == $_POST['password']){
        	//be sure to set session id name and timeout time in php.ini, httponly=true
    		session_start();
    		//take user to database if username/password combo found
        	$_SESSION['username'] = $_POST['username'];
        	header('Location: brightmoorPantry.php');
			exit;
	} else {
  	  echo 'Invalid password.<br><br> <a href="login.php">Return to login form</a>';
	}
			
	//$result->free();
}
/*
$stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");

$stmt->bind_param('s', $fieldArray[0]);

if ($stmt->execute() == TRUE) {

$result = $stmt->get_result();*/

/*while ($row = $result->fetch_array(MYSQLI_ASSOC)){
	$hash = $row['password'];
}*/
/*
//echo $hash;
if(!isset($_POST['password'])){
	$_POST['password'] = NULL;
}
if (password_verify($_POST['password'], $hash)) {
    //echo 'Password is valid!';
    session_start();
        //take user to database if username/password combo found
        $_SESSION['username'] = $_POST['username'];
        header('Location: brightmoorPantry.php');
		exit;
} else {
    echo 'Invalid password.<br><br> <a href="login.php">Return to login form</a>';
}*/
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

else {
	echo "User Name not found.";
	echo "<br><br> <a href=\"login.php\">Return to login form</a>";
}
    
$conn->close();

?>

</section>
</body>
</html>