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
<title>New Appointment record created</title>
</head>
<body style="background-color:Beige;">

<h1>Brightmoor Connection Database</h1>

<?php

//connect to database using php script
require_once ('mysql_connect.php');

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
	require_once ('nav.html');
} else {
	echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
	echo "<br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
}

$conn->close();

?>

</body>
</html>
