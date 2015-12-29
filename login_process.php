<?php
require_once ('mysql_connect.php');
//create array from form input
$posts = array($_POST[username],$_POST[password]);

//create array to place either post value or NULL
$fieldArray = array();

//loop through each post value and assign NULL value if no input
$arrlength = count($posts);
for($x = 0; $x < $arrlength; $x++) {
    $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
}

$stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ? AND password = ?");

$stmt->bind_param('ss', $fieldArray[0], $fieldArray[1]);

if ($stmt->execute() == TRUE) {

$result = $stmt->get_result();
if ($result->num_rows > 0) {

        session_start();
        $_SESSION["username"] = $_POST[username];

        while ($row = $result->fetch_array(MYSQLI_NUM))
        {
        
            foreach ($row as $r)
            {
                print "$r ";
            }
            print "\n";
        }
        
} else {
	echo 'No results <br />';
	}

  echo '<a href="login.php">Return to login form</a><br /><a href="brightmoorPantry.php">Go to database</a>';
  
} else {
	echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
	echo "<br><br> <a href=\"login.php\">Return to login form</a>";
}

$conn->close();

?>