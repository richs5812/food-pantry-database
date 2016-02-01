<?php
//code for session variable to make sure user is logged in
session_start();
if(empty($_SESSION['username'])) {
header('Location: login.php');
exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="db_styles.css">
<!--include javascript for pattern field for phone numbers, dates, etc-->
<script src="jquery.js" type="text/javascript"></script>
<script src="jquery.maskedinput.js" type="text/javascript"></script>

<!-- validate form script-->
<script>
function validateDate() {
	var x = document.forms["InsertAppointment"]["NewAppointmentPicker"].value;
    if (x == null || x == "") {
        alert("Please choose an appointment date.");
        return false;
    }
}
function validateReferral() {
	var x = document.forms["InsertReferral"]["ReferralType"].value;
    if (x == null || x == "") {
        alert("Please choose a referral type.");
        return false;
    }
}
</script>

<!--script for pattern masks-->
<script>
jQuery(function($){
   $("#EnrollmentDate").mask("99/99/99",{placeholder:"mm/dd/yy"});
   $("#CoatOrderDate").mask("99/99/99",{placeholder:"mm/dd/yy"});
   $("input[type=text]#HomePhoneNumber").mask("(999)999-9999");
   $("input[type=text]#CellPhoneNumber").mask("(999)999-9999");
});
</script>

<!-- date pickers-->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  $(function() {
    $( "#ReferralDate" ).datepicker({dateFormat: "mm/dd/y"});
  });
  </script>
  <script>
  $(function() {
    $( "#AppointmentDate" ).datepicker({dateFormat: "mm/dd/y"});
  });
  </script>
  <!-- end date picker-->
<title>Brightmoor Connection Database</title>
</head>
<body>

<!-- include functions to create form items-->
<?php require_once ('functions.php');?>
<header>
	<img src="images/brightmoor_logo.jpg" width=500px>
	<h1>Brightmoor Connection Database</h1>
</header>
<?php
// Echo session variables that were set on previous page
//echo "user name is " . $_SESSION["username"] . ".<br>";
?>

<nav>
<?php require_once ('nav.html'); ?>
</nav>

<section>

<?php 

require_once ('client_drop_down.php');

?>

<!--client information form-->
<div class="formStyle">

<?php
//connect to database using php script
require_once ('mysql_connect.php');
if (!isset($_POST['ClientID']))
{
//If not isset -> set with dummy value
$_POST['ClientID'] = "undefined";
} else {
$client = mysqli_real_escape_string($conn, $_POST['ClientID']);
}

if (!isset($_GET['id']))
{
//If not isset -> set with dummy value
$_GET['id'] = "undefined";
} else {
$client = mysqli_real_escape_string($conn, $_GET['id']);
}

//$posts = array($_POST['ClientID']);
$stmt = $conn->prepare("SELECT * FROM Clients WHERE ClientID=?");
$stmt->bind_param('s', $client);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

/*
$sql = "SELECT * FROM Clients WHERE ClientID='$client'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();*/
echo '
<form action="update.php" method="post">';

echo '
    <h2>Client Information:</h2>
    
    <h3>Basic info:</h3>
<input type="hidden" name="ClientID" value="' .$row["ClientID"]. '" />';

textInput("FirstName", "First Name");

textInput("LastName", "Last Name");

numberInput("Age", "Age", "100");

echo'
<div class="fieldWrapper" id="gender"><label for="Gender">Gender: </label><select name="Gender" id="Gender">
  			<option value="'.$row["Gender"].'">' .$row["Gender"]. '</option>
 			 <option value="F">F</option>
 			 <option value="M">M</option>
		</select></div>';


checkBox("Pregnant", "Pregnant");

checkBox("AddressVerified", "Address Verified");

textInput("Address", "Address");

textInput("Address2", "Address 2");

echo '<div class="fieldWrapper" id="zipCode"><label class="zipCode" for="ZipCode">Zip Code: </label><input class="zipCode" type="text" name="ZipCode" id="ZipCode" value="'.$row["ZipCode"].'"  maxlength="5" size="5"/></div>';

textInput("HomePhoneNumber", "Home Phone Number");

textInput("CellPhoneNumber", "Cell Phone Number");

echo '<div class="fieldWrapper">';
emailInput("EmailAddress", "Email Address");
echo '</div>';

echo '<h3>Family Information:</h3>';

numberInput("FamilySize", "Family Size");

numberInput("AdultsNumber", "Number of Adults");

numberInput("ChildrenNumber", "Number of Children");
   
echo '<h4>Age Range:</h4>';

numberInput("AgeRange05", "0-5");

numberInput("AgeRange617", "6-17");

numberInput("AgeRange1829", "18-29");

numberInput("AgeRange3039", "30-39");

numberInput("AgeRange4049", "40-49");

numberInput("AgeRange5064", "50-64");

numberInput("AgeRange65", "65+");

echo '<h3>Household Information. This information is to help determine future services!</h3>
<p style="display:inline;">Do you have access to facilities to prepare food (stove/oven): </p>';

$hasStoveChecked = NULL;
$noStoveChecked = NULL;

 if ($row["HasStove"]=='Yes'){
 	$hasStoveChecked = 'checked';
 }
 if ($row["HasStove"]=='No'){
 	$noStoveChecked = 'checked';
 }

echo '

<label for="stoveYes">Yes</label><input type="radio" name="HasStove" id="stoveYes" value="Yes" '.$hasStoveChecked.'>
 <label for="stoveNo">No</label><input type="radio" name="HasStove" id="stoveNo" value="No" '.$noStoveChecked.'><br /><br />';
/*
checkBox("StoveYes", "Yes");

checkBox("StoveNo", "No");
*/
echo 'Do you need DHS provider services? ';

checkBox("StateEmergencyRelease", "State Emergency Release");

checkBox("FoodStampAssistance", "Food Stamp Assistance");

checkBox("LimitedHealthServicesReferral", "Limited Health Services Referral");

textInput("AdditionalServices", "Additional Services");

echo '<label for="OtherNotes">Other/Notes: </label><textarea id="OtherNotes" name="OtherNotes" >' .$row["OtherNotes"]. '</textarea>     ';

dateInput("EnrollmentDate", "Enrollment Date");

echo '<h3>Christmas Coat Orders:</h3>';

checkBox("CoatOrder", "Coat Ordered");

echo 'Have you participated in previous Christmas food distributions in Brightmoor? ';
checkBox("PreviousChristmasFoodYes", "Yes");

checkBox("PreviousChristmasFoodNo", "No");

dateInput("CoatOrderDate", "Coat Order Date");

 if ($row["ClientID"]!=""){ 
echo'<input type="submit" value="Update Client Information"/></form>';
} else{
echo '<input type="submit" value="Enter new client"/></form>
   		';
}

?>
</div><!--end form style -->

<!--family member form -->
<div class="formStyle">
<?php

/*
$familyMembersSql = "SELECT FamilyMembers.*, Clients.ClientID FROM FamilyMembers INNER JOIN Clients ON FamilyMembers.ClientID=Clients.ClientID WHERE FamilyMembers.ClientID='$row[ClientID]' ORDER BY FamilyMembers.Age DESC, FamilyMembers.FamilyMemberName ASC";
$familyMembersResult = $conn->query($familyMembersSql);*/

echo '<h3>Family Information</h3>';

if ($row["ClientID"]!=""){
echo '<div class="overflow-x">';
$familyMembersStmt = $conn->prepare("SELECT FamilyMembers.*, Clients.ClientID FROM FamilyMembers INNER JOIN Clients ON FamilyMembers.ClientID=Clients.ClientID WHERE FamilyMembers.ClientID=? ORDER BY FamilyMembers.Age DESC, FamilyMembers.FamilyMemberName ASC");
$familyMembersStmt->bind_param('s', $row['ClientID']);
$familyMembersStmt->execute();
$familyMembersResult = $familyMembersStmt->get_result();

	     echo '  
            <table border="1">
		<tr>
    		<th>Name</th>
    		<th>Relationship</th> 
    		<th>Age</th> 
    		<th>Gender</th>
    		<th></th>
		</tr>';
if ($familyMembersResult->num_rows > 0) {
    while($familyMembersRow = $familyMembersResult->fetch_assoc()) {
        //output existing family member info
        echo '
        <form action="UpdateFamilyMember.php" method="post">
<input type="hidden" name="FamilyMemberID" value="'.$familyMembersRow["FamilyMemberID"].'" />
<input type="hidden" name="ClientID" value="' .$row["ClientID"]. '" />
<tr>
	<td><label for="FamilyMemberName'.$familyMembersRow["FamilyMemberID"].'"></label><input type="text" id="FamilyMemberName'.$familyMembersRow["FamilyMemberID"].'" name="FamilyMemberName" value="' .$familyMembersRow["FamilyMemberName"].'"/></td>
	<td><label for="Relationship'.$familyMembersRow["FamilyMemberID"].'"></label><input type="text" id="Relationship'.$familyMembersRow["FamilyMemberID"].'" name="Relationship" value="'.$familyMembersRow["Relationship"].'"/></td>
	<td><label for="FamilyMemberAge'.$familyMembersRow["FamilyMemberID"].'"></label><input type="number" id="FamilyMemberAge'.$familyMembersRow["FamilyMemberID"].'" class="rightLeftMargin" name="FamilyMemberAge" value="' .$familyMembersRow["Age"]. '" min="0" max="120"></td>
	<td><label for="FamilyMemberGender'.$familyMembersRow["FamilyMemberID"].'"></label><select id="FamilyMemberGender'.$familyMembersRow["FamilyMemberID"].'" name="FamilyMemberGender">
  			<option value="' .$familyMembersRow["Gender"]. '">' .$familyMembersRow["Gender"]. '</option>
 			 <option value="F">F</option>
 			 <option value="M">M</option>
		</select></td>
	<td class="whiteSpaceNoWrap"><div class="displayInlineBlock"><input type="submit" name="Update" value="Update Record" /></div>
	<div class="displayInlineBlock"><input type="submit" name="Delete" value="Delete Record" onClick="return confirm(\'Are you sure you want to delete this family member record?\');" /></div></td>
</tr>
</form>
';
    }
    }
    
    if (!isset($_POST['autofocus']))
{
//If not isset -> set with dummy value
$_POST['autofocus'] = "undefined";
}
    
   echo '<form action="InsertFamilyMember.php" method="post">
<input type="hidden" name="ClientID" value="' .$row["ClientID"]. '" />   
<tr>
	<td><label for="FamilyMemberName"></label><input type="text" id="FamilyMemberName" name="FamilyMemberName" '.$_POST["autofocus"].'/></td>
	<td><label for="Relationship"></label><input type="text" id="Relationship" name="Relationship"/></td>
	<td><label for="FamilyMemberAge"></label><input type="number" class="rightLeftMargin" id="FamilyMemberAge" name="FamilyMemberAge" min="0" max="120"></td>
	<td><label for="FamilyMemberGender"></label><select id="FamilyMemberGender" name="FamilyMemberGender">
  			<option value=""></option>
 			 <option value="F">F</option>
 			 <option value="M">M</option>
		</select></td>
	<td><input type="submit" value="Enter new family member"/></td>
</tr>
</form>
</table>
</div>
';
   } else {
	echo 'Save new client before entering family information.';
	}
	
?>
</div><!--end form style -->

<!--referrals form -->
<div class="formStyle">
<?php

/*
$referralsSql = "SELECT Referrals.*, Clients.ClientID FROM Referrals INNER JOIN Clients ON Referrals.ClientID=Clients.ClientID WHERE Referrals.ClientID='$row[ClientID]'";
$referralsResult = $conn->query($referralsSql);*/
echo '<h3>Referral Information</h3>';
		
if ($row["ClientID"]!=""){ 
echo '<div class="overflow-x">';

$referralsStmt = $conn->prepare("SELECT Referrals.*, Clients.ClientID FROM Referrals INNER JOIN Clients ON Referrals.ClientID=Clients.ClientID WHERE Referrals.ClientID=?");
$referralsStmt->bind_param('s', $row['ClientID']);
$referralsStmt->execute();
$referralsResult = $referralsStmt->get_result();

	     echo '
	    <table border="1">
		<tr>
    		<th>Referral Type</th>
    		<th>Referral Date</th> 
    		<th>Notes</th> 
    		<th></th>
		</tr>';
if ($referralsResult->num_rows > 0) {
    while($referralsRow = $referralsResult->fetch_assoc()) {
        echo '
        <form action="UpdateReferral.php" method="post">
<input type="hidden" name="ReferralID" value="'.$referralsRow["ReferralID"].'" />
<input type="hidden" name="ClientID" value="' .$row["ClientID"]. '" />
<tr>';
//display referral type in drop down menu
	echo '<td>';
	echo '<select name="ReferralType">';
	$referralTypeSql = "SELECT ReferralType FROM ReferralType;";
	$referralTypeResult = $conn->query($referralTypeSql);
	if ($referralTypeResult->num_rows > 0) {
    // output data of each row 
    while($referralTypeRow = $referralTypeResult->fetch_assoc()) {
    	if ($referralTypeRow["ReferralType"]==$referralsRow["ReferralType"]){
    		$selected = "selected";
    	}else{
    		$selected = "";
    		}
        echo '<option value="'. $referralTypeRow['ReferralType'] .'" '.$selected.'>'. $referralTypeRow['ReferralType'] .'</option>';
    }
	} else {
		echo "0 results";
	}
	echo '</select></td>';
	//end referral type drop down menu

	//date picker for existing referral dates
	echo '<script>
	  $(function() {
    	$( "#ReferralDate'.$referralsRow["ReferralID"].'" ).datepicker({dateFormat: "mm/dd/y"});
  	});
  	</script>';

	//display formatted referral date
	if ($referralsRow["ReferralDate"]!=NULL){
	$referralDisplayDate = date("m/d/y", strtotime($referralsRow["ReferralDate"]));
	}

echo '
	<td class="center"><input type="text" id="ReferralDate'.$referralsRow["ReferralID"].'" name="ReferralDate" class="dateTextLength" value="'.$referralDisplayDate.'"/></td>
	<td><input type="text" id="ReferralNotes" name="ReferralNotes" value="' .$referralsRow["ReferralNotes"]. '"></td>
	<td class="whiteSpaceNoWrap"><div class="displayInlineBlock"><input type="submit" name="Update" value="Update Record"/></div>
	<div class="displayInlineBlock"><input type="submit" name="Delete" value="Delete Record" onClick="return confirm(\'Are you sure you want to delete this referral record?\');"/></div></td>
</tr>
</form>
';
    }
    }
    
   echo '<form action="InsertReferral.php" name="InsertReferral" method="post" onsubmit="return validateReferral()">
<input type="hidden" name="ClientID" value="' .$row["ClientID"]. '" />   
<tr>';

//display referral type in drop down menu
	echo '<td>';
	echo '<select name="ReferralType">
			<option value=""> </option>';
	$referralTypeSql = "SELECT ReferralType FROM ReferralType;";
	$referralTypeResult = $conn->query($referralTypeSql);
	if ($referralTypeResult->num_rows > 0) {
    // output data of each row 
    while($referralTypeRow = $referralTypeResult->fetch_assoc()) {
    	echo '<option value="'. $referralTypeRow['ReferralType'] .'" '.$selected.'>'. $referralTypeRow['ReferralType'] .'</option>';
    }
	} else {
		echo "0 results";
	}
	echo '</select></td>';
	//end referral type drop down menu

echo '
	<td class="center"><input type="text" id="ReferralDate" name="ReferralDate" class="dateTextLength"/></td>
	<td><input type="text" id="ReferralNotes" name="ReferralNotes"/></td>
	<td><input type="submit" value="Enter new referral"/></td>
</tr>
</table>
</form>
</div>
';
   } else {
	echo 'Save new client before entering referral information.';
	}
?>
</div><!--end form style -->

<!--appointments form -->
<div class="formStyle">
<?php

/*$appointmentsSql = "SELECT Appointments.*, Clients.ClientID FROM Appointments INNER JOIN Clients ON Appointments.ClientID=Clients.ClientID WHERE Appointments.ClientID='$row[ClientID]' ORDER BY Appointments.AppointmentDate ASC";
$appointmentsResult = $conn->query($appointmentsSql);*/

echo '<h3>Appointment Information</h3>';
		
if ($row["ClientID"]!=""){ 
echo '<div class="overflow-x">';

$appointmentsStmt = $conn->prepare("SELECT Appointments.*, Clients.ClientID FROM Appointments INNER JOIN Clients ON Appointments.ClientID=Clients.ClientID WHERE Appointments.ClientID=? ORDER BY Appointments.AppointmentDate ASC");
$appointmentsStmt->bind_param('s', $row['ClientID']);
$appointmentsStmt->execute();
$appointmentsResult = $appointmentsStmt->get_result();

	     echo '
        <table border="1">
		<tr>
    		<th>Appointment Date</th>
    		<th>Appointment Status</th> 
    		<th>Notes</th> 
    		<th></th>
		</tr>';
if ($appointmentsResult->num_rows > 0) {
    while($appointmentsRow = $appointmentsResult->fetch_assoc()) {
        echo '
        <form action="UpdateAppointment.php" method="post">
<input type="hidden" name="AppointmentID" value="'.$appointmentsRow['AppointmentID'].'" />
<input type="hidden" name="ClientID" value="' .$row['ClientID']. '" />
<tr>';

	//date picker for existing appointment dates
	echo '<script>
	  $(function() {
    	$( "#AppointmentDate'.$appointmentsRow["AppointmentID"].'" ).datepicker({dateFormat: "mm/dd/y"});
  	});
  	</script>';
	
	//display appointment date
	if ($appointmentsRow["AppointmentDate"]!=NULL){
	$appointmentDisplayDate = date("m/d/y", strtotime($appointmentsRow["AppointmentDate"]));
	} else {
		$appointmentDisplayDate = NULL;
	}

	echo '<td class="center"><input type="text" id="AppointmentDate'.$appointmentsRow["AppointmentID"].'" name="AppointmentDate" class="dateTextLength" value="'.$appointmentDisplayDate.'"/></td>';
	//end display existing appointment date

	//display appointment status in drop down menu
	echo '<td>';
	echo '<select name="AppointmentStatus">';
	$apptStatusSql = "SELECT AppointmentStatus FROM AppointmentStatus;";
	$apptStatusResult = $conn->query($apptStatusSql);
	if ($apptStatusResult->num_rows > 0) {
    // output data of each row 
    while($apptStatusRow = $apptStatusResult->fetch_assoc()) {
    	if ($apptStatusRow['AppointmentStatus']==$appointmentsRow['AppointmentStatus']){
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

echo '
	<td><input type="text" id="AppointmentNotes" name="AppointmentNotes" value="' .$appointmentsRow['AppointmentNotes']. '"></td>
	<td class="whiteSpaceNoWrap"><div class="displayInlineBlock"><input type="submit" name="Update" value="Update Appointment"/></div>
	<div class="displayInlineBlock"><input type="submit" name="Delete" value="Delete Appointment" onClick="return confirm(\'Are you sure you want to delete this appointment record?\');"/></div></td>
</tr>
</form>
';
    }
    }
    
   echo '<form action="InsertAppointment.php" method="post" name="InsertAppointment" onsubmit="return validateDate()">
<input type="hidden" name="ClientID" value="' .$row["ClientID"]. '" />   
<tr>
	<td class="center"><input type="text" class="dateTextLength" id="AppointmentDate" name="NewAppointmentPicker"/></td>';
	
	//display appointment status in drop down menu
	echo '<td>';
	echo '<select name="AppointmentStatus">';
	$apptStatusSql = "SELECT AppointmentStatus FROM AppointmentStatus;";
	$apptStatusResult = $conn->query($apptStatusSql);
	if ($apptStatusResult->num_rows > 0) {
    // output data of each row 
    while($apptStatusRow = $apptStatusResult->fetch_assoc()) {
    	echo '<option value="'. $apptStatusRow['AppointmentStatus'] .'">'. $apptStatusRow['AppointmentStatus'] .'</option>';
    }
	} else {
		echo "0 results";
	}
	echo '</select></td>';
	//end appointment status drop down menu

	
echo'
	<td><input type="text" id="AppointmentNotes" name="AppointmentNotes"/></td>
	<td><input type="submit" value="Enter new appointment"/></td>
</tr>
</table>
</form>
</div>
';
   } else {
	echo 'Save new client before entering appointment information.';
	}
?>
</div><!--end form style -->

<?php

$conn->close();

?>
</section>

</body>
</html>
