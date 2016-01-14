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
<title>New Referral Created</title>
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

//format dates for MySQL from input format
    date_default_timezone_set('America/Detroit');
	if($_POST[ReferralDate]!=NULL){
	$sqlFormattedReferralDate = date("Y-m-d", strtotime($_POST[ReferralDate]));
	} else {
	$sqlFormattedReferralDate = NULL;
	}

$posts = array($_POST[ClientID],$_POST[ReferralType],$sqlFormattedReferralDate,$_POST[ReferralNotes]);

$fieldArray = array();

$arrlength = count($posts);
for($x = 0; $x < $arrlength; $x++) {
    $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
}

$stmt = $conn->prepare("INSERT INTO Referrals (ClientID, ReferralType, ReferralDate, ReferralNotes) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3]);

if ($stmt->execute() == TRUE) {
  echo 'New referral record created succesfully.<br><br>
	<form action="brightmoorPantry.php" method="post">
    <input type="hidden" name="ClientID" value="' .$_POST[ClientID]. '" />
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