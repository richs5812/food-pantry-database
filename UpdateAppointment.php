<?php
//code for session variable to make sure user is logged in
require_once('session_check.php');

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="db_styles.css">
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appointment Updated</title>
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

//sql to pull client name for 'return to client page' button
$clientButtonStmt = $conn->prepare("SELECT FirstName, LastName FROM Clients WHERE ClientID=?");
$clientButtonStmt->bind_param('s', $_POST['ClientID']);
$clientButtonStmt->execute();
$clientButtonResult = $clientButtonStmt->get_result();

/*$clientButtonSql = "SELECT FirstName, LastName FROM Clients WHERE ClientID='$_POST[ClientID]'";
	$clientButtonResult = $conn->query($clientButtonSql);*/
	if ($clientButtonResult->num_rows > 0) {
    // output data of each row 
    while($clientButtonRow = $clientButtonResult->fetch_assoc()) {
		   $clientName = $clientButtonRow['FirstName'] ." ".$clientButtonRow['LastName'];
    }
}

//format dates for MySQL from input format
date_default_timezone_set('America/Detroit');
if (!isset($_POST['previousFormDate']))
{
//If not isset -> set with dummy value
$_POST['previousFormDate'] = NULL;
}
$previousDisplayDate = date("m/d/y", strtotime($_POST['previousFormDate']));

//check if Update button or Delete button was clicked
if (isset($_POST['Update'])) {
	if($_POST['AppointmentDate']!=NULL){
	$sqlFormattedAppointmentDate = date("Y-m-d", strtotime($_POST['AppointmentDate']));
	} else {
	$sqlFormattedAppointmentDate = NULL;
	}
    
    //code to update record
    $posts = array($_POST['ClientID'],$sqlFormattedAppointmentDate,$_POST['AppointmentStatus'],$_POST['AppointmentNotes'],$_POST['AppointmentID']);

	$fieldArray = array();

	$arrlength = count($posts);
	//echo $arrlength;
	for($x = 0; $x < $arrlength; $x++) {
	    $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
	}

	$stmt = $conn->prepare("UPDATE Appointments SET ClientID=?, AppointmentDate=?, AppointmentStatus=?, AppointmentNotes=? WHERE AppointmentID=?");

	$stmt->bind_param('sssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4]);

	if ($stmt->execute() == TRUE) {
		echo 'Appointment updated successfully.<br><br>';
		/*if (!isset($_POST['previousFormDate']))
		{
		//If not isset -> set with dummy value
		$_POST['previousFormDate'] = "undefined";
		}	*/
		if ($_POST['previousFormDate'] != NULL){
		//coming from Appointments page
		echo '<form action="appointments.php" method="post">
			<input type="hidden" name="datepicker" value="' .$_POST['previousFormDate']. '" />
  			<input type="submit" value="Return to ' .$previousDisplayDate. ' appointments" />
		</form>';
		
		if($previousDisplayDate != $_POST['AppointmentDate']){
			echo'
			<form action="appointments.php" method="post">
				<input type="hidden" name="datepicker" value="' .$_POST['AppointmentDate']. '" />
  				<input type="submit" value="Go to ' .$_POST['AppointmentDate']. ' appointments" />
			</form>
			';
		}
		
		echo'
		<form action="brightmoorPantry.php" method="post">
    		<input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    		<input type="submit" value="View '.$clientName.'\'s Client Page" />
   		</form>
   		';
   		}	else {
   			//coming from brightmoorPantry page
   			echo'
			<form action="brightmoorPantry.php" method="post">
    			<input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    			<input type="submit" value="Return to '.$clientName.'\'s Client Page" />
   			</form>
   			';
   			
   			echo'
			<form action="appointments.php" method="post">
				<input type="hidden" name="datepicker" value="' .$_POST['AppointmentDate']. '" />
  				<input type="submit" value="Go to ' .$_POST['AppointmentDate']. ' appointments" />
			</form>
			';
   		}   		
		
		/*echo'
		<form action="brightmoorPantry.php" method="post">
    		<input type="hidden" name="ClientID" value="' .$_POST[ClientID]. '" />
    		<input type="submit" value="View '.$_POST[clientName].'\'s Client Page" />
   		</form>
   		';*/
	} else {
		echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
		echo "<br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
	}

} else if (isset($_POST['Delete'])) {
    //delete action if Delete button was clicked
    $posts = array($_POST['AppointmentID']);
    $stmt = $conn->prepare("DELETE FROM Appointments WHERE AppointmentID=?");
    $stmt->bind_param('s', $posts[0]);
  
    if ($stmt->execute() == TRUE) {
    echo "Appointment record deleted.<br><br>";
    if ($_POST['previousFormDate'] != NULL){
    echo '
		<form action="appointments.php" method="post">
			<input type="hidden" name="datepicker" value="' .$_POST['previousFormDate']. '" />
  			<input type="submit" value="Return to ' .$previousDisplayDate. ' appointments" />
		</form>';
	
		echo'
		<form action="brightmoorPantry.php" method="post">
    		<input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    		<input type="submit" value="View '.$clientName.'\'s Client Page" />
   		</form>
   		';
	} else {
			echo'
		<form action="brightmoorPantry.php" method="post">
    		<input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    		<input type="submit" value="Return to '.$clientName.'\'s Client Page" />
   		</form>
   		';
	}
    
} else {
    echo "Error: " . $stmt->error;
}
    
  /*  
    $sql="DELETE FROM Appointments WHERE AppointmentID='$_POST[AppointmentID]'";
    if ($conn->query($sql) === TRUE) {
    echo "Appointment record deleted.<br><br>";
    
    

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}*/
}

$conn->close();

?>

</section>
</body>
</html>
