<!DOCTYPE html>
<html>
<head>
<title>Brightmoor Connection Database</title>
</head>
<body style="background-color:Beige;">

<!--include scripts for pattern field for phone numbers, dates, etc-->
<script src="jquery.js" type="text/javascript"></script>
<script src="jquery.maskedinput.js" type="text/javascript"></script>

<!--script for pattern masks-->
<script>
jQuery(function($){
   $("#EnrollmentDate").mask("99/99/99",{placeholder:"mm/dd/yy"});
   $("#CoatOrderDate").mask("99/99/99",{placeholder:"mm/dd/yy"});
   $("#HomePhoneNumber").mask("(999)999-9999");
   $("#CellPhoneNumber").mask("(999)999-9999");
});
</script>

<?php

//set timezone for strtotime php function to convert date to MySQL format from input format
date_default_timezone_set('America/Detroit');

//functions to create various form items
function checkBox($valueName, $labelName) {
	global $row;
    if ($row[$valueName]!='1'){
echo '<label for="'.$valueName.'">'.$labelName.': </label><input type="hidden" name="'.$valueName.'" value="0" />
			<input type="checkbox" name="'.$valueName.'" id="'.$valueName.'" value="1" />';
} else {
	echo '<label for="'.$valueName.'">'.$labelName.': </label><input type="checkbox" name="'.$valueName.'" id="'.$valueName.'" value="1" checked/>';
}	
}

function textInput($valueName, $labelName) {
	global $row;
	
	echo '<label for="'.$valueName.'">'.$labelName.': </label><input type="text" name="'.$valueName.'" id="'.$valueName.'" value="' .$row[$valueName].'"/>';	
}

function numberInput($valueName, $labelName, $maxNum = "99999") {
	global $row;
	
	echo '<label for="'.$valueName.'">'.$labelName.': </label><input type="number" name="'.$valueName.'" id="'.$valueName.'" value="' .$row[$valueName].'" min="0" max="'.$maxNum.'"/>';	
}

function dateInput($valueName, $labelName) {
	global $row;
	
	//$originalDate = $row[$valueName];
	if ($row[$valueName]!=NULL){
	$displayDate = date("m-d-y", strtotime($row[$valueName]));
	}
		if ($row[ClientID] == ""){
		$displayDate="  /  /  ";
		}

	echo '<label for="'.$valueName.'">'.$labelName.': </label><input type="date" name="'.$valueName.'" id="'.$valueName.'" value="' .$displayDate.'"/>';
}

function emailInput($valueName, $labelName) {
	global $row;
	
	echo '<label for="'.$valueName.'">'.$labelName.': </label><input type="email" name="'.$valueName.'" id="'.$valueName.'" value="' .$row[$valueName] .'"/>';	
}

?>

<h1>Brightmoor Connection Database</h1>
<?php require_once ('nav.html'); ?>

<!--start drop down menu-->
<form id="dropDownMenu" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
<select name="ClientID" onchange="change()">
<option value="NewRecord">New Record</option>

<?php 
require_once ('mysql_connect.php');

$sql = "SELECT ClientID, FirstName, LastName FROM Clients ORDER BY LastName ASC, FirstName ASC;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row 
    while($row = $result->fetch_assoc()) {
    	if ($row[ClientID]==$_POST[ClientID]){
    		$selected = "selected";
    	}else{
    		$selected = "";
    		}
        echo '<option value="'. $row['ClientID'] .'" '.$selected.'>'. $row['LastName'] .', '. $row['FirstName'] .'</option>';
    }
} else {
    echo "0 results";
}
?>
</select>

<script>
function change(){
    document.getElementById("dropDownMenu").submit();
}
</script>

</form>
<!--end drop down menu-->

<?php

//connect to database using php script
require_once ('mysql_connect.php');

$sql = "SELECT * FROM Clients WHERE ClientID='$_POST[ClientID]'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo '

<form action="update.php" method="post">';

echo '
<fieldset>
    <legend>Client Information:</legend>
    
    <fieldset>
    <legend>Basic info:</legend>
<input type="hidden" name="ClientID" value="' .$row[ClientID]. '" />';

textInput("FirstName", "First Name");

textInput("LastName", "Last Name");

checkBox("Pregnant", "Pregnant");

checkBox("AddressVerified", "Address Verified");

numberInput("Age", "Age", "100");

echo'
<label for="Gender">Gender: </label><select name="Gender" id="Gender">
  			<option value="'.$row[Gender].'">' .$row[Gender]. '</option>
 			 <option value="F">F</option>
 			 <option value="M">M</option>
		</select>';

echo '<br>';

textInput("Address", "Address");

textInput("Address2", "Address 2");

echo '<label for="ZipCode">Zip Code: </label><input type="text" name="ZipCode" id="ZipCode" value="'.$row[ZipCode].'" maxlength="5" size="5"/>';

echo '<br>';

textInput("HomePhoneNumber", "Home Phone Number");

textInput("CellPhoneNumber", "Cell Phone Number");

emailInput("EmailAddress", "Email Address");


echo '</fieldset>
	<fieldset>
    <legend>Family Information:</legend>';

numberInput("FamilySize", "FamilySize");

numberInput("AdultsNumber", "Adults Number");

numberInput("ChildrenNumber", "Children Number");

echo '
	<fieldset>
    <legend>Age Range:</legend>';

numberInput("AgeRange05", "0-5");

numberInput("AgeRange617", "6-17");

numberInput("AgeRange1829", "18-29");

numberInput("AgeRange3039", "30-39");

echo '<br>';

numberInput("AgeRange4049", "40-49");

numberInput("AgeRange5064", "50-64");

numberInput("AgeRange65", "65+");

echo '</fieldset></fieldset>
	<fieldset>
    <legend>Household Information. This information is to help determine future services!</legend>Do you have access to facilities to prepare food (stove/oven): ';

checkBox("StoveYes", "Yes");

checkBox("StoveNo", "No");

echo '<br>Do you need DHS provider services? ';

checkBox("StateEmergencyRelease", "State Emergency Release");

checkBox("FoodStampAssistance", "Food Stamp Assistance");

checkBox("LimitedHealthServicesReferral", "Limited Health Services Referral");

textInput("AdditionalServices", "Additional Services");

echo '<br><label for="OtherNotes">Other/Notes: </label><textarea id="OtherNotes" name="OtherNotes" rows="4" cols="50">' .$row[OtherNotes]. '</textarea>     ';

dateInput("EnrollmentDate", "Enrollment Date");

echo '</fieldset>
	<fieldset>
    <legend>Christmas Coat Orders:</legend>';

checkBox("CoatOrder", "Coat Ordered");

echo '<br>Have you participated in previous Christmas food distributions in Brightmoor? ';
checkBox("PreviousChristmasFoodYes", "Yes");

checkBox("PreviousChristmasFoodNo", "No");

dateInput("CoatOrderDate", "Coat Order Date");

echo '</fieldset>';
 if ($row[ClientID]!=""){ 
echo'<input type="submit" value="Update Client Information"/></fieldset></form>';
} else{
echo '<input type="submit" value="Enter new client"/></fieldset></form>
   		';
}

//family members code
$familyMembersSql = "SELECT FamilyMembers.*, Clients.ClientID FROM FamilyMembers INNER JOIN Clients ON FamilyMembers.ClientID=Clients.ClientID WHERE FamilyMembers.ClientID='$_POST[ClientID]' ORDER BY FamilyMembers.Age DESC, FamilyMembers.FamilyMemberName ASC";
$familyMembersResult = $conn->query($familyMembersSql);

echo '

<fieldset>
    <legend>Family Information:</legend>
    
';

if ($familyMembersResult->num_rows > 0) {
    while($familyMembersRow = $familyMembersResult->fetch_assoc()) {
        
        echo '
        <form action="UpdateFamilyMember.php" method="post">
<input type="hidden" name="FamilyMemberID" value="'.$familyMembersRow[FamilyMemberID].'" />
<input type="hidden" name="ClientID" value="' .$row[ClientID]. '" />
<label for="FamilyMemberName'.$familyMembersRow[FamilyMemberID].'">Name: </label><input type="text" id="FamilyMemberName'.$familyMembersRow[FamilyMemberID].'" name="FamilyMemberName" value="' .$familyMembersRow[FamilyMemberName].'"/>
<label for="Relationship'.$familyMembersRow[FamilyMemberID].'">Relationship: </label><input type="text" id="Relationship'.$familyMembersRow[FamilyMemberID].'" name="Relationship" value="'.$familyMembersRow[Relationship].'"/>
<label for="FamilyMemberAge'.$familyMembersRow[FamilyMemberID].'">Age: </label><input type="number" id="FamilyMemberAge'.$familyMembersRow[FamilyMemberID].'" name="FamilyMemberAge" value="' .$familyMembersRow[Age]. '">
<label for="FamilyMemberGender'.$familyMembersRow[FamilyMemberID].'">Gender: </label><select id="FamilyMemberGender'.$familyMembersRow[FamilyMemberID].'" name="FamilyMemberGender">
  			<option value="' .$familyMembersRow[Gender]. '">' .$familyMembersRow[Gender]. '</option>
 			 <option value="F">F</option>
 			 <option value="M">M</option>
		</select>

<input type="submit" name="Update" value="Update Record"/>
<input type="submit" name="Delete" value="Delete Record"/>
</form>
';
    }
    
} else {
	echo 'Save new client before entering family information';
	}
    
 if ($row[ClientID]!=""){ 
   echo '<form action="InsertFamilyMember.php" method="post">
<input type="hidden" name="ClientID" value="' .$row[ClientID]. '" />   
<label for="FamilyMemberName">Name: </label><input type="text" id="FamilyMemberName" name="FamilyMemberName" value="' .$familyMembersRow[FamilyMemberName].'"/>
<label for="Relationship">Relationship: </label><input type="text" id="Relationship" name="Relationship" value="'.$familyMembersRow[Relationship].'"/>
<label for="FamilyMemberAge">Age: </label><input type="number" id="FamilyMemberAge" name="FamilyMemberAge" value="' .$familyMembersRow[Age]. '">
<label for="FamilyMemberGender">Gender: </label><select id="FamilyMemberGender" name="FamilyMemberGender">
  			<option value="' .$familyMembersRow[Gender]. '">' .$familyMembersRow[Gender]. '</option>
 			 <option value="F">F</option>
 			 <option value="M">M</option>
		</select>
<input type="submit" value="Enter new family member"/>
</form>
';
   }

$conn->close();

?>

</body>
</html>