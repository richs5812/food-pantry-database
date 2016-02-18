<?php
//code for session variable to make sure user is logged in
require_once('session_check.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>New Family Member record created</title>
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="db_styles.css">
</head>
<body>

<header>
	<?php require_once ('header.html');?>
</header>

<nav>
<?php require_once ('nav.html'); ?>
</nav>
<section>
<?php

//connect to database using php script
require_once ('storehouse_connect.php');

$posts = array($_POST['ClientID'],$_POST['FamilyMemberName'],$_POST['FamilyMemberAge'],$_POST['FamilyMemberGender'],$_POST['Relationship']);

$fieldArray = array();

$arrlength = count($posts);
for($x = 0; $x < $arrlength; $x++) {
   /* $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
    }*/
     if (empty($posts[$x])) {
     	$fieldArray[$x] = NULL;
     } else {
     	//test input
		$posts[$x] = trim($posts[$x]);
		$posts[$x] = stripslashes($posts[$x]);
		$posts[$x] = htmlspecialchars($posts[$x]);
		$fieldArray[$x] = $posts[$x];
     }
}

$stmt = $conn->prepare("INSERT INTO FamilyMembers (ClientID, FamilyMemberName, Age, Gender, Relationship) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4]);

if ($stmt->execute() == TRUE) {
  echo 'New family member record created succesfully.<br><br>
	<form action="storehousePantry.php" method="post">
    <input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    <input type="hidden" name="autofocus" value="autofocus" />
    <input type="submit" value="Return to Client Page" autofocus/>
   </form>
    ';
} else {
	echo "Error: <br>". $stmt->error;
	echo "<br><br> <a href=\"storehousePantry.php\">Return to database</a>";
}

$conn->close();

?>
</section>
</body>
</html>