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
<link rel="stylesheet" href="db_styles.css">
<!--include javascript for pattern field for phone numbers, dates, etc-->
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

<!-- date picker-->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  $(function() {
    $( "#ReferralDate" ).datepicker({dateFormat: "mm/dd/y"});
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
<!--client information form-->
<?php 

echo '<br />';
require_once ('client_drop_down.php');

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

?>

<?php
//family members code
$familyMembersSql = "SELECT FamilyMembers.*, Clients.ClientID FROM FamilyMembers INNER JOIN Clients ON FamilyMembers.ClientID=Clients.ClientID WHERE FamilyMembers.ClientID='$_POST[ClientID]' ORDER BY FamilyMembers.Age DESC, FamilyMembers.FamilyMemberName ASC";
$familyMembersResult = $conn->query($familyMembersSql);

echo '
<fieldset>
	<legend>Family Information:</legend>
';

 if ($row[ClientID]!=""){ 
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
<input type="hidden" name="FamilyMemberID" value="'.$familyMembersRow[FamilyMemberID].'" />
<input type="hidden" name="ClientID" value="' .$row[ClientID]. '" />
<tr>
	<td><label for="FamilyMemberName'.$familyMembersRow[FamilyMemberID].'"></label><input type="text" id="FamilyMemberName'.$familyMembersRow[FamilyMemberID].'" name="FamilyMemberName" value="' .$familyMembersRow[FamilyMemberName].'"/></td>
	<td><label for="Relationship'.$familyMembersRow[FamilyMemberID].'"></label><input type="text" id="Relationship'.$familyMembersRow[FamilyMemberID].'" name="Relationship" value="'.$familyMembersRow[Relationship].'"/></td>
	<td><label for="FamilyMemberAge'.$familyMembersRow[FamilyMemberID].'"></label><input type="number" id="FamilyMemberAge'.$familyMembersRow[FamilyMemberID].'" name="FamilyMemberAge" value="' .$familyMembersRow[Age]. '"></td>
	<td><label for="FamilyMemberGender'.$familyMembersRow[FamilyMemberID].'"></label><select id="FamilyMemberGender'.$familyMembersRow[FamilyMemberID].'" name="FamilyMemberGender">
  			<option value="' .$familyMembersRow[Gender]. '">' .$familyMembersRow[Gender]. '</option>
 			 <option value="F">F</option>
 			 <option value="M">M</option>
		</select></td>
	<td><input type="submit" name="Update" value="Update Record"/>
	<input type="submit" name="Delete" value="Delete Record" onClick="return confirm(\'Are you sure you want to delete this family member record?\');"/></td>
</tr>
</form>
';
    }
    }
    
   echo '<form action="InsertFamilyMember.php" method="post">
<input type="hidden" name="ClientID" value="' .$row[ClientID]. '" />   
<tr>
	<td><label for="FamilyMemberName"></label><input type="text" id="FamilyMemberName" name="FamilyMemberName" '.$_POST[autofocus].'/></td>
	<td><label for="Relationship"></label><input type="text" id="Relationship" name="Relationship"/></td>
	<td><label for="FamilyMemberAge"></label><input type="number" id="FamilyMemberAge" name="FamilyMemberAge"></td>
	<td><label for="FamilyMemberGender"></label><select id="FamilyMemberGender" name="FamilyMemberGender">
  			<option value=""></option>
 			 <option value="F">F</option>
 			 <option value="M">M</option>
		</select></td>
	<td><input type="submit" value="Enter new family member"/></td>
</tr>
</table>
</fieldset>
</form>
';
   } else {
	echo 'Save new client before entering family information.</fieldset>';
	}
	
?>

<?php
//referrals code
$referralsSql = "SELECT Referrals.*, Clients.ClientID FROM Referrals INNER JOIN Clients ON Referrals.ClientID=Clients.ClientID WHERE Referrals.ClientID='$_POST[ClientID]'";
$referralsResult = $conn->query($referralsSql);
echo '<fieldset>
		<legend>Referral Information</legend>';
		
if ($row[ClientID]!=""){ 
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
        //output existing family member info
        echo '
        <form action="UpdateReferral.php" method="post">
<input type="hidden" name="ReferralID" value="'.$referralsRow[ReferralID].'" />
<input type="hidden" name="ClientID" value="' .$row[ClientID]. '" />
<tr>';
//display referral type in drop down menu
	echo '<td>';
	echo '<select name="ReferralType">';
	$referralTypeSql = "SELECT ReferralType FROM ReferralType;";
	$referralTypeResult = $conn->query($referralTypeSql);
	if ($referralTypeResult->num_rows > 0) {
    // output data of each row 
    while($referralTypeRow = $referralTypeResult->fetch_assoc()) {
    	if ($referralTypeRow[ReferralType]==$referralsRow[ReferralType]){
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
    	$( "#ReferralDate'.$referralsRow[ReferralID].'" ).datepicker({dateFormat: "mm/dd/y"});
  	});
  	</script>';

//	<td><input type="text" id="ReferralType'.$referralsRow[FamilyMemberID].'" name="ReferralType" value="' .$referralsRow[ReferralType].'"/></td>
echo '
	<td><input type="text" id="ReferralDate'.$referralsRow[ReferralID].'" name="ReferralDate" value="'.$referralsRow[ReferralDate].'"/></td>
	<td><input type="text" id="ReferralNotes" name="ReferralNotes" value="' .$referralsRow[ReferralNotes]. '"></td>
	<td><input type="submit" name="Update" value="Update Record"/>
	<input type="submit" name="Delete" value="Delete Record" onClick="return confirm(\'Are you sure you want to delete this referral record?\');"/></td>
</tr>
</form>
';
    }
    }
    
   echo '<form action="InsertReferral.php" method="post">
<input type="hidden" name="ClientID" value="' .$row[ClientID]. '" />   
<tr>';

//display referral type in drop down menu
	echo '<td>';
	echo '<select name="ReferralType">
			<option></option>';
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
	<td><input type="text" id="ReferralDate" name="ReferralDate" onclick="this.innerHTML=Date()"/></td>
	<td><input type="text" id="ReferralNotes" name="ReferralNotes"/></td>
	<td><input type="submit" value="Enter new referral"/></td>
</tr>
</table>
</fieldset>
</form>
';
   } else {
	echo 'Save new client before entering referral information.</fieldset>';
	}

$conn->close();

?>
</section>

</body>
</html>
