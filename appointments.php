<?php
//code for session variable to make sure user is logged in
require_once('session_check.php');
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="db_styles.css">
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<title>Brightmoor Connection Database - Appointments</title>
<script>
function validateClient() {
    var x = document.forms["InsertAppointment"]["ClientID"].value;
    if (x == null || x == "") {
        alert("Please select a client.");
        return false;
    }
}
function validateDate() {
	var y = document.forms["InsertAppointment"]["NewAppointmentPicker"].value;
    if (y == null || y == "") {
        alert("Please choose an appointment date.");
        return false;
    }
}
</script>
<!--include javascript for pattern field for phone numbers, dates, etc-->
<script src="jquery.js" type="text/javascript"></script>
<script src="jquery.maskedinput.js" type="text/javascript"></script>

<!--script for pattern masks-->
<script>
jQuery(function($){
   //$("#datepicker").mask("99/99/99",{placeholder:"mm/dd/yy"});
  // $("#AppointmentDate").mask("99/99/99",{placeholder:"mm/dd/yy"});
   $("#HomePhoneNumber").mask("(999)999-9999");
   $("#CellPhoneNumber").mask("(999)999-9999");
});
</script>

<!-- date picker-->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  $(function() {
    $( "#datepicker" ).datepicker({
    			dateFormat: "mm/dd/y",
    		onSelect : function (dateText, inst) {
          $('#datePicker').submit(); // <-- SUBMIT
  }
    		});
  });
  </script>
  <script>
  $(function() {
    $( "#NewAppointmentPicker" ).datepicker({dateFormat: "mm/dd/y"});
  });
  </script>
  <!-- end date picker-->
</head>
<body>

<!-- include functions to create form items-->
<?php require_once ('functions.php');?>
<header>
<img src="images/brightmoor_logo.jpg" width=500px>
<h1>Brightmoor Connection Database - Appointments</h1>
</header>
<?php
// Echo session variables that were set on previous page
//echo "user name is " . $_SESSION["username"] . ".<br>";
?>

<nav>
<?php require_once ('nav.html'); ?>
</nav>
<section>

<div class="formStyle">
<form id="datePicker" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">

<?php 
if (!isset($_POST['datepicker']))
{
//If not isset -> set with dummy value
$_POST['datepicker'] = NULL;
}
if ($_POST['datepicker'] != NULL)
{
	$datePickerDate = date("m/d/y", strtotime($_POST['datepicker']));
} else {
	$datePickerDate = NULL;
}

echo'<div class="fieldwrapper" id="divdatepicker"><label for="datepicker">Date: </label><input type="text" name="datepicker" id="datepicker" class="dateTextLength" value="'.$datePickerDate.'" size="10"></div>';

?>

</form>

<?php
require_once ('mysql_connect.php');
date_default_timezone_set('America/Detroit');
//$todaysDate = date("Y-m-d");
//echo strtotime($todaysDate);
if ($_POST['datepicker'] == NULL){

echo '<h2>Upcoming Appointments:</h2>';

$appointmentsSql = "SELECT DISTINCT AppointmentDate FROM Appointments ORDER BY AppointmentDate ASC";
$appointmentsResult = $conn->query($appointmentsSql);
//date("m/d/y", strtotime($_POST['datepicker'])) //$_POST['datepicker']
if ($appointmentsResult->num_rows > 0) {
    while($appointmentsRow = $appointmentsResult->fetch_assoc()) {	
    	if($appointmentsRow['AppointmentDate'] >= date("Y-m-d")){
    		echo '<h3>'.date("l, F j, Y", strtotime($appointmentsRow['AppointmentDate'])).'</h3>';
    		//echo "<br />";
    		$countSql = "SELECT COUNT(AppointmentDate) AS total FROM Appointments WHERE AppointmentDate='$appointmentsRow[AppointmentDate]'";
    		$countResult = $conn->query($countSql);
    		if ($countResult->num_rows > 0) {
  				while($countRow = $countResult->fetch_assoc()) {
    				echo $countRow['total'];
    				if($countRow['total']==1){
    				echo " appointment scheduled.";
    				} else {
    					echo " appointments scheduled.";
    				}
    				echo '<form id="datePicker" action="'.$_SERVER["PHP_SELF"].'" method="post" style="display: inline;">';
    					echo'
    					<input type="hidden" name="datepicker" value="' .$appointmentsRow['AppointmentDate']. '" />
    					<input type="submit" name="View" value=">> View Appointments"/>';
    				echo '</form><br />';
    			}
    		}
    	}
    }   
}
} else {
	//if date has been selected

//format dates for MySQL from input format and assign NULL if no input
//if($_POST['datepicker']!=NULL){
$sqlFormattedAppointmentDate = date("Y-m-d", strtotime($_POST['datepicker']));
$prettyDate = date("F j, Y", strtotime($_POST['datepicker']));
 /*else {
	$sqlFormattedAppointmentDate = NULL;
	}*/
	
//$sql = "SELECT * FROM Appointments WHERE AppointmentDate='$sqlFormattedAppointmentDate'";
$sql = "SELECT Appointments.*, Clients.FirstName, Clients.LastName FROM Appointments INNER JOIN Clients ON Appointments.ClientID=Clients.ClientID WHERE AppointmentDate='$sqlFormattedAppointmentDate' ORDER BY Clients.LastName ASC, Clients.FirstName ASC";
$result = $conn->query($sql);

//if ($_POST['datepicker'] != "") {
	echo '<div style="overflow-x:auto;">
	<table border="1">
	<tr>
    	<th>Client</th>
    	<th>Appointment Date</th> 
    	<th>Appointment Status</th> 
    	<th>Notes</th>
    	<th></th>
	</tr>';
	if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {	
	//form to update and delete appointments
	echo '<form action="UpdateAppointment.php" method="post">
	<input type="hidden" name="AppointmentID" value="' .$row['AppointmentID']. '" />
	<input type="hidden" name="previousFormDate" value="' .$_POST['datepicker']. '" />
	';
	/*
	$clientName = $row[FirstName] . " " . $row[LastName];
	echo '<input type="hidden" name="clientName" value="' .$clientName. '" />';
	*/
	//display client name
	echo '<tr><td class="center">';
	//<a target="_blank" href="source.php?id='.$row[pic_id].'">
	echo '<input type="hidden" name="ClientID" value="' .$row['ClientID']. '" />';
	echo '<a class="noLinkStyle" href="brightmoorPantry.php?id=' .$row['ClientID'].'">'.$row['LastName'].', '.$row['FirstName'].'</a>';
	echo '</td>';
	
	//date picker for existing appointment dates
	echo '<script>
	  $(function() {
    	$( "#AppointmentDate'.$row['AppointmentID'].'" ).datepicker({dateFormat: "mm/dd/y"});
  	});
  	</script>';

	//display appointment date
	if ($row['AppointmentDate']!=NULL){
	$appointmentDisplayDate = date("m/d/y", strtotime($row['AppointmentDate']));
	}

	echo '<td class="center"><input type="text" id="AppointmentDate'.$row['AppointmentID'].'" name="AppointmentDate" class="dateTextLength" value="'.$appointmentDisplayDate.'" /></td>';
	//end display existing appointment date
	
	/*//display client appointment date	
	echo '<td>';
	noLabelDateInput("AppointmentDate");
	echo '</td>';*/
	
//display appointment status in drop down menu
	echo '<td>';
	echo '<select name="AppointmentStatus">';
	$apptStatusSql = "SELECT AppointmentStatus FROM AppointmentStatus;";
	$apptStatusResult = $conn->query($apptStatusSql);
	if ($apptStatusResult->num_rows > 0) {
    // output data of each row 
    while($apptStatusRow = $apptStatusResult->fetch_assoc()) {
    	if ($apptStatusRow['AppointmentStatus']==$row['AppointmentStatus']){
    		$selected = "selected";
    	}else{
    		$selected = "";
    		}
        echo '<option value="'. $apptStatusRow['AppointmentStatus'] .'" '.$selected.'>'. $apptStatusRow['AppointmentStatus'] .'</option>';
    }
	} else {
		echo "0 results";
	}
	echo '</select></td>';
	//end appointment status drop down menu

	echo '<td>';
	noLabelTextInput("AppointmentNotes");
	echo '</td>';
	
	echo '
	<td class="whiteSpaceNoWrap"><div class="displayInlineBlock"><input type="submit" name="Update" value="Update Appointment"/></div>
	<div class="displayInlineBlock"><input type="submit" name="Delete" value="Delete Appointment" onClick="return confirm(\'Are you sure you want to delete the appointment?\');"/></div></td>
	</tr>
	</form>
	';
	
    } //end while loop of results
} else {
		echo "<br />No appointments scheduled yet for $prettyDate - create an appointment below.<br /><br />";
	}
    //insert new appointment
    echo '<form name="InsertAppointment" action="InsertAppointment.php" onsubmit="return validateClient()" method="post">
    	<input type="hidden" name="fromAppointmentForm" value="1" />'; 	
    	//let insertAppointment know this is coming from apppointment form, not client page
	echo '<tr><td>';
	echo '<select name="ClientID">
	<option value=""></option>';
	$dropDownSql = "SELECT ClientID, FirstName, LastName FROM Clients ORDER BY LastName ASC, FirstName ASC;";
	$dropDownResult = $conn->query($dropDownSql);
	if ($dropDownResult->num_rows > 0) {
    // output data of each row 
    while($dropDownRow = $dropDownResult->fetch_assoc()) {
    	if ($dropDownRow[ClientID]==$row[ClientID]){
    		$selected = "selected";
    	}else{
    		$selected = "";
    		}
        echo '<option value="'. $dropDownRow['ClientID'] .'" '.$selected.'>'. $dropDownRow['LastName'] .', '. $dropDownRow['FirstName'] .'</option>';
    }
	} else {
		echo "0 results";
	}
	echo '</select></td>';
	//end client drop down menu
	echo'<td class="center"><input type="text" name="NewAppointmentPicker" class="dateTextLength" id="NewAppointmentPicker" value="'.$datePickerDate.'"></td>';
	//display appointment status in drop down menu
	echo '<td>';
	echo '<select name="AppointmentStatus">';
	$apptStatusSql = "SELECT AppointmentStatus FROM AppointmentStatus;";
	$apptStatusResult = $conn->query($apptStatusSql);
	if ($apptStatusResult->num_rows > 0) {
    // output data of each row 
    while($apptStatusRow = $apptStatusResult->fetch_assoc()) {
    	if ($apptStatusRow[AppointmentStatus]==$row[AppointmentStatus]){
    		$selected = "selected";
    	}else{
    		$selected = "";
    		}
        echo '<option value="'. $apptStatusRow['AppointmentStatus'] .'" '.$selected.'>'. $apptStatusRow['AppointmentStatus'] .'</option>';
    }
	} else {
		echo "0 results";
	}
	echo '</select></td>';
	//end appointment status drop down menu
	echo '<td>';
	noLabelTextInput("AppointmentNotes");
	echo '</td>';
	echo'<td><input type="submit" name="Insert" value="Create Appointment"/></td>';
    echo '</tr></form></table></div>';
}
$conn->close();

?>
<!--
<p id="demo">date goes here</p>

<script>
document.getElementById("datepicker").addEventListener("change", displayDate);

function displayDate() {
    document.getElementById("demo").innerHTML = Date();
}
</script>-->
</div>
</section>
</body>
</html>
