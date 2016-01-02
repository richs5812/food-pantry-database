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
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<title>Brightmoor Connection Database - Appointments</title>
</head>
<body style="background-color:Beige;">

<!--include javascript for pattern field for phone numbers, dates, etc-->
<script src="jquery.js" type="text/javascript"></script>
<script src="jquery.maskedinput.js" type="text/javascript"></script>

<!--script for pattern masks-->
<script>
jQuery(function($){
   //$("#datepicker").mask("99/99/99",{placeholder:"mm/dd/yy"});
   $("#CoatOrderDate").mask("99/99/99",{placeholder:"mm/dd/yy"});
   $("#HomePhoneNumber").mask("(999)999-9999");
   $("#CellPhoneNumber").mask("(999)999-9999");
});
</script>

<!-- include functions to create form items-->
<?php require_once ('functions.php');?>

<h1>Brightmoor Connection Database - Appointments</h1>
<?php
// Echo session variables that were set on previous page
//echo "user name is " . $_SESSION["username"] . ".<br>";
?>

<?php require_once ('nav.html'); ?>
 
<!-- date picker-->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  $(function() {
    $( "#datepicker" ).datepicker({dateFormat: "mm/dd/y"});
  });
  </script>
  <!-- end date picker-->

<form id="datePicker" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">

<p>Date: <input type="text" name="datepicker" id="datepicker"></p>

<input type="submit"/>

</form>

<?php
require_once ('mysql_connect.php');

//format dates for MySQL from input format and assign NULL if no input
if($_POST[datepicker]!=NULL){
$sqlFormattedAppointmentDate = date("Y-m-d", strtotime($_POST[datepicker]));
} else {
	$sqlFormattedAppointmentDate = NULL;
	}
	
//$sql = "SELECT * FROM Appointments WHERE AppointmentDate='$sqlFormattedAppointmentDate'";
$sql = "SELECT Appointments.*, Clients.FirstName FROM Appointments INNER JOIN Clients ON Appointments.ClientID=Clients.ClientID WHERE AppointmentDate='$sqlFormattedAppointmentDate'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    	
	//output existing appointment info
	//display client name in drop down menu
	echo '<select name="ClientID">';
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
	echo '</select>';
	//end drop down menu
	dateInput("AppointmentDate", "Appointment Date");
	//appt status here
	textInput("Notes", "Notes");
	
	echo '<br />';
    } //end while loop of results
} else {
	echo "no results";
}
$conn->close();

?>

</body>
</html>