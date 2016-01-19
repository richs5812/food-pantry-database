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
<title>New Family Member record created</title>
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="db_styles.css">
</head>
<body style="background-color:Beige;">

<header>
	<img src="images/brightmoor_logo.jpg" width=500px>
	<h1>Brightmoor Connection Database</h1>
</header>

<nav>
<?php require_once ('nav.html'); ?>
</nav>

<?php

//connect to database using php script
require_once ('mysql_connect.php');

$posts = array($_POST['ClientID'],$_POST['FamilyMemberName'],$_POST['FamilyMemberAge'],$_POST['FamilyMemberGender'],$_POST['Relationship']);

$fieldArray = array();

$arrlength = count($posts);
for($x = 0; $x < $arrlength; $x++) {
    $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
}

$stmt = $conn->prepare("INSERT INTO FamilyMembers (ClientID, FamilyMemberName, Age, Gender, Relationship) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4]);

if ($stmt->execute() == TRUE) {
  echo 'New family member record created succesfully.<br><br>
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

$conn->close();

?>

</body>
</html>