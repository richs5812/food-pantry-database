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
<link rel="stylesheet" href="db_styles.css">
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Appointment record created</title>
</head>
<body style="background-color:Beige;">

<h1>Brightmoor Connection Database</h1>

<nav>
<?php require_once ('nav.html'); ?>
</nav>

<?php

//connect to database using php script
require_once ('mysql_connect.php');

//sql to pull client name for 'return to client page' button
$clientButtonSql = "SELECT FirstName, LastName FROM Clients WHERE ClientID='$_POST[ClientID]'";
	$clientButtonResult = $conn->query($clientButtonSql);
	if ($clientButtonResult->num_rows > 0) {
    // output data of each row 
    while($clientButtonRow = $clientButtonResult->fetch_assoc()) {
		   $clientName = $clientButtonRow[FirstName] ." ".$clientButtonRow[LastName];
    }
}

//format dates for MySQL from input format
    date_default_timezone_set('America/Detroit');
	if($_POST[NewAppointmentPicker]!=NULL){
	$sqlFormattedAppointmentDate = date("Y-m-d", strtotime($_POST[NewAppointmentPicker]));
	} else {
	$sqlFormattedAppointmentDate = NULL;
	}

$posts = array($_POST[ClientID],$sqlFormattedAppointmentDate,$_POST[AppointmentStatus],$_POST[AppointmentNotes]);

$fieldArray = array();
//assign null values if blank
$arrlength = count($posts);
for($x = 0; $x < $arrlength; $x++) {
    $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
}

$stmt = $conn->prepare("INSERT INTO Appointments (ClientID, AppointmentDate, AppointmentStatus, AppointmentNotes) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3]);

if ($stmt->execute() == TRUE) {
	echo 'New Appointment record created succesfully.<br><br>';
	
	if ($_POST[fromAppointmentForm] != NULL){
	//coming from appointments page
	echo'
		<form action="appointments.php" method="post">
			<input type="hidden" name="datepicker" value="' .$_POST[NewAppointmentPicker]. '" />
  			<input type="submit" value="Go to ' .$_POST[NewAppointmentPicker]. ' appointments" />
		</form>
		';
		echo'
		<form action="brightmoorPantry.php" method="post">
    		<input type="hidden" name="ClientID" value="' .$_POST[ClientID]. '" />
    		<input type="submit" value="View '.$clientName.'\'s Client Page" />
   		</form>
   		';
   		} else {
   		echo'
		<form action="brightmoorPantry.php" method="post">
    		<input type="hidden" name="ClientID" value="' .$_POST[ClientID]. '" />
    		<input type="submit" value="Return to '.$clientName.'\'s Client Page" />
   		</form>
   		';
   			echo'
		<form action="appointments.php" method="post">
			<input type="hidden" name="datepicker" value="' .$_POST[NewAppointmentPicker]. '" />
  			<input type="submit" value="Go to ' .$_POST[NewAppointmentPicker]. ' appointments" />
		</form>
		';
   		}
} else {
	echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
	echo "<br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
}

/*if ($stmt->execute() == TRUE) {
		echo 'Appointment updated successfully.<br><br>
		<form action="appointments.php" method="post">
			<input type="hidden" name="datepicker" value="' .$_POST[previousFormDate]. '" />
  			<input type="submit" value="Return to ' .$previousDisplayDate. ' appointments" />
		</form>';
		
		if($previousDisplayDate != $_POST[AppointmentDate]){
			echo'
			<form action="appointments.php" method="post">
				<input type="hidden" name="datepicker" value="' .$_POST[AppointmentDate]. '" />
  				<input type="submit" value="Go to ' .$_POST[AppointmentDate]. ' appointments" />
			</form>
			';
		}
		
		echo'
		<form action="brightmoorPantry.php" method="post">
    		<input type="hidden" name="ClientID" value="' .$_POST[ClientID]. '" />
    		<input type="submit" value="View '.$_POST[clientName].'\'s Client Page" />
   		</form>
   		';
	} else {
		echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
		echo "<br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
	}*/

$conn->close();

?>

</body>
</html>
