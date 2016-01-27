<?php
//code for session variable to make sure user is logged in
session_start();
if(empty($_SESSION["username"])) {
header('Location: login.php');
exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Family Member Updated</title>
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="db_styles.css">
</head>
<body>

<header>
	<img src="images/brightmoor_logo.jpg" width=500px>
	<h1>Brightmoor Connection Database</h1>
</header>

<nav>
<?php require_once ('nav.html'); ?>
</nav>
<section>
<?php

//connect to database using php script
require_once ('mysql_connect.php');

//check if family member Update button or Delete button was clicked
if (isset($_POST['Update'])) {
    //code to update record
    $posts = array($_POST['FamilyMemberName'],$_POST['FamilyMemberAge'],$_POST['FamilyMemberGender'],$_POST['Relationship'],$_POST['FamilyMemberID']);

	$fieldArray = array();

	$arrlength = count($posts);
	//echo $arrlength;
	for($x = 0; $x < $arrlength; $x++) {
	    $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
	}

	$stmt = $conn->prepare("UPDATE FamilyMembers SET FamilyMemberName=?, Age=?, Gender=?, Relationship=? WHERE FamilyMemberID=?");

	$stmt->bind_param('sssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4]);

	if ($stmt->execute() == TRUE) {
	   echo 'Family member record updated successfully.<br><br>
 		<form action="brightmoorPantry.php" method="post">
    	<input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    	<input type="hidden" name="autofocus" value="autofocus" />
    	<input type="submit" value="Return to Client Page" autofocus/>
   		</form>
    	';
	} else {
		echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
		echo "<br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
	}

} else if (isset($_POST['Delete'])) {
    //delete action if Delete button was clicked

    $posts = array($_POST['FamilyMemberID']);
    $stmt = $conn->prepare("DELETE FROM FamilyMembers WHERE FamilyMemberID=?");
    $stmt->bind_param('s', $posts[0]);
  
    if ($stmt->execute() == TRUE) {
    echo "Family member record deleted.<br><br>";
    echo '<form action="brightmoorPantry.php" method="post">
    <input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    <input type="submit" autofocus value="Return to Client Page" />
   </form>
    ';
} else {
    echo "Error: " . $stmt->error;
}
}/*
    $sql="DELETE FROM FamilyMembers WHERE FamilyMemberID='$_POST[FamilyMemberID]'";
    if ($conn->query($sql) === TRUE) {
    echo "Family Member record deleted.<br><br>";
    echo '<form action="brightmoorPantry.php" method="post">
    <input type="hidden" name="ClientID" value="' .$_POST[ClientID]. '" />
    <input type="submit" autofocus value="Return to Client Page" />
   </form>
    ';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}*/

$conn->close();

?>
</section>
</body>
</html>