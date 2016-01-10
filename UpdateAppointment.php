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
<title>Appointment Updated</title>
</head>
<body style="background-color:Beige;">

<h1>Brightmoor Connection Database</h1>

<?php

//connect to database using php script
require_once ('mysql_connect.php');

//check if Update button or Delete button was clicked
if (isset($_POST['Update'])) {
    //format dates for MySQL from input format
    date_default_timezone_set('America/Detroit');
	if($_POST[AppointmentDate]!=NULL){
	$sqlFormattedAppointmentDate = date("Y-m-d", strtotime($_POST[AppointmentDate]));
	} else {
	$sqlFormattedAppointmentDate = NULL;
	}
    
    //code to update record
    $posts = array($_POST[ClientID],$sqlFormattedAppointmentDate,$_POST[AppointmentStatus],$_POST[AppointmentNotes],$_POST[AppointmentID]);

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
		require_once ('nav.html');
	} else {
		echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
		echo "<br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
	}

} else if (isset($_POST['Delete'])) {
    //delete action if Delete button was clicked
    $sql="DELETE FROM Appointments WHERE AppointmentID='$_POST[AppointmentID]'";
    if ($conn->query($sql) === TRUE) {
    echo "Appointment record deleted.<br><br>";
		require_once ('nav.html');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

$conn->close();

?>

</body>
</html>