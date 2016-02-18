<?php
//code for session variable to make sure user is logged in
require_once('session_check.php');
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="db_styles.css">
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Appointment record created</title>
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
	if($_POST['NewAppointmentPicker']!=NULL){
	$sqlFormattedAppointmentDate = date("Y-m-d", strtotime($_POST['NewAppointmentPicker']));
	} else {
	$sqlFormattedAppointmentDate = NULL;
	}

$posts = array($_POST['ClientID'],$sqlFormattedAppointmentDate,$_POST['AppointmentStatus'],$_POST['AppointmentNotes']);

$fieldArray = array();
//assign null values if blank
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

$stmt = $conn->prepare("INSERT INTO Appointments (ClientID, AppointmentDate, AppointmentStatus, AppointmentNotes) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3]);

if ($stmt->execute() == TRUE) {
	echo 'New Appointment record created succesfully.<br><br>';
	if (!isset($_POST['fromAppointmentForm']))
	{
	//If not isset -> set with dummy value
	$_POST['fromAppointmentForm'] = NULL;
	}
	if ($_POST['fromAppointmentForm'] != NULL){
	//coming from appointments page
	echo'
		<form action="storehouseAppointments.php" method="post">
			<input type="hidden" name="datepicker" value="' .$_POST['NewAppointmentPicker']. '" />
  			<input type="submit" value="Go to ' .$_POST['NewAppointmentPicker']. ' appointments" />
		</form>
		';
		echo'
		<form action="storehousePantry.php" method="post">
    		<input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    		<input type="submit" value="View '.$clientName.'\'s Client Page" />
   		</form>
   		';
   		} else {
   		echo'
		<form action="storehousePantry.php" method="post">
    		<input type="hidden" name="ClientID" value="' .$_POST['ClientID']. '" />
    		<input type="submit" value="Return to '.$clientName.'\'s Client Page" />
   		</form>
   		';
   			echo'
		<form action="appointments.php" method="post">
			<input type="hidden" name="datepicker" value="' .$_POST['NewAppointmentPicker']. '" />
  			<input type="submit" value="Go to ' .$_POST['NewAppointmentPicker']. ' appointments" />
		</form>
		';
   		}
} else {
	echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
	echo "<br><br> <a href=\"storehousePantry.php\">Return to database</a>";
}


$conn->close();

?>
</section>
</body>
</html>
