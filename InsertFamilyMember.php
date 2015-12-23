<!DOCTYPE html>
<html>
<head>
<title>New Family Member record created</title>
</head>
<body style="background-color:Beige;">

<h1>Brightmoor Connection Database</h1>

<?php

//connect to database using php script
require_once ('mysql_connect.php');

$posts = array($_POST[ClientID],$_POST[FamilyMemberName],$_POST[FamilyMemberAge],$_POST[FamilyMemberGender],$_POST[Relationship]);

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
    <input type="hidden" name="ClientID" value="' .$_POST[ClientID]. '" />
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