<?php
//code for session variable to make sure user is logged in
require_once('session_check.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>Referral Updated</title>
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
require_once ('mysql_path.php');
require_once ($mysql_path);

//check if family member Update button or Delete button was clicked
if (isset($_POST['Update'])) {

	//format dates for MySQL from input format
    date_default_timezone_set('America/Detroit');
	if($_POST['ReferralDate']!=NULL){
	$sqlFormattedReferralDate = date("Y-m-d", strtotime($_POST['ReferralDate']));
	} else {
	$sqlFormattedReferralDate = NULL;
	}

    //code to update record
    $posts = array($_POST['ReferralType'],$sqlFormattedReferralDate,$_POST['ReferralNotes'],$_POST['ReferralID']);

	$fieldArray = array();

	$arrlength = count($posts);
	//echo $arrlength;
	for($x = 0; $x < $arrlength; $x++) {
	    $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
	}

	$stmt = $conn->prepare("UPDATE Referrals SET ReferralType=?, ReferralDate=?, ReferralNotes=? WHERE ReferralID=?");

	$stmt->bind_param('ssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3]);

	if ($stmt->execute() == TRUE) {
	   echo 'Referral record updated successfully.<br><br>
 		<form action="brightmoorPantry.php" method="post">
    	<input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    	
    	<input type="submit" value="Return to Client Page" autofocus/>
   		</form>
    	';
	} else {
		echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
		echo "<br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
	}

} else if (isset($_POST['Delete'])) {
    //delete action if Delete button was clicked
    $posts = array($_POST['ReferralID']);
    $stmt = $conn->prepare("DELETE FROM Referrals WHERE ReferralID=?");
    $stmt->bind_param('s', $posts[0]);
    
    //$sql="DELETE FROM Referrals WHERE ReferralID='$_POST[ReferralID]'";
    //if ($conn->query($sql) === TRUE) {
    if ($stmt->execute() == TRUE) {
    echo "Referral record deleted.<br><br>";
    echo '<form action="brightmoorPantry.php" method="post">
    <input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    <input type="submit" autofocus value="Return to Client Page" />
   </form>
    ';
} else {
    echo "Error: " . $stmt->error;
}
}

$conn->close();

?>
</section>
</body>
</html>