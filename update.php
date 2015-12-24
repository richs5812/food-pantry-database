<!DOCTYPE html>
<html>
<head>
<title>Record Updated</title>
</head>
<body style="background-color:Beige;">

<h1>Brightmoor Connection Database</h1>

<?php

//connect to database using php script
require_once ('mysql_connect.php');
date_default_timezone_set('America/Detroit');

//figure out if new client and send to insert.php if true. else update existing client
if ($_POST[ClientID]==""){
	require_once ('insert.php');
	} else {
$sql = "SELECT * FROM Clients WHERE ClientID='$_POST[ClientID]'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

//format dates for MySQL from input format
if($_POST[EnrollmentDate]!=NULL){
$sqlFormattedEnrollmentDate = date("Y-m-d", strtotime($_POST[EnrollmentDate]));
} else {
	$sqlFormattedEnrollmentDate = NULL;
	}
if($_POST[CoatOrderDate]!=NULL){
$sqlFormattedCoatOrderDate = date("Y-m-d", strtotime($_POST[CoatOrderDate]));
} else {
	$sqlFormattedCoatOrderDate = NULL;
	}

//create array from form input
$posts = array($_POST[FirstName],$_POST[LastName],$_POST[Address],$_POST[Address2],$_POST[HomePhoneNumber],$_POST[ZipCode],$_POST[Age],$_POST[Gender],$_POST[Pregnant],$sqlFormattedEnrollmentDate,$_POST[AddressVerified],$_POST[EmailAddress],$_POST[CellPhoneNumber],$_POST[FamilySize],$_POST[AdultsNumber],$_POST[AgeRange05],$_POST[AgeRange617],$_POST[AgeRange1829],$_POST[AgeRange3039],$_POST[AgeRange4049],$_POST[AgeRange5064],$_POST[AgeRange65],$_POST[StoveYes],$_POST[StoveNo],$_POST[StateEmergencyRelease],$_POST[FoodStampAssistance],$_POST[LimitedHealthServicesReferral],$_POST[AdditionalServices],$_POST[OtherNotes],$_POST[CoatOrder],$_POST[PreviousChristmasFoodYes],$_POST[PreviousChristmasFoodNo],$sqlFormattedCoatOrderDate,$_POST[ChildrenNumber],$_POST[ClientID]);

//create array to place either post value or NULL
$fieldArray = array();

//loop through each post value and assign NULL value if no input
$arrlength = count($posts);
for($x = 0; $x < $arrlength; $x++) {
    $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
}

$stmt = $conn->prepare("UPDATE Clients SET FirstName=?, LastName=?, Address=?, Address2=?, HomePhoneNumber=?, ZipCode=?, Age=?, Gender=?, Pregnant=?, EnrollmentDate=?, AddressVerified=?, EmailAddress=?, CellPhoneNumber=?, FamilySize=?, AdultsNumber=?, AgeRange05=?, AgeRange617=?, AgeRange1829=?, AgeRange3039=?, AgeRange4049=?, AgeRange5064=?, AgeRange65=?, StoveYes=?, StoveNo=?, StateEmergencyRelease=?, FoodStampAssistance=?, LimitedHealthServicesReferral=?, AdditionalServices=?, OtherNotes=?, CoatOrder=?, PreviousChristmasFoodYes=?, PreviousChristmasFoodNo=?, CoatOrderDate=?, ChildrenNumber=? WHERE ClientID=?");

$stmt->bind_param('sssssssssssssssssssssssssssssssssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4], $fieldArray[5], $fieldArray[6], $fieldArray[7], $fieldArray[8], $fieldArray[9], $fieldArray[10], $fieldArray[11], $fieldArray[12], $fieldArray[13], $fieldArray[14], $fieldArray[15], $fieldArray[16], $fieldArray[17], $fieldArray[18], $fieldArray[19], $fieldArray[20], $fieldArray[21], $fieldArray[22], $fieldArray[23], $fieldArray[24], $fieldArray[25], $fieldArray[26], $fieldArray[27], $fieldArray[28], $fieldArray[29], $fieldArray[30], $fieldArray[31], $fieldArray[32], $fieldArray[33], $fieldArray[34]);

if ($stmt->execute() == TRUE) {
  echo 'Client record updated successfully.<br><br>
	<form action="brightmoorPantry.php" method="post">
    <input type="hidden" name="ClientID" value="' .$_POST[ClientID]. '" />
    <input type="submit" value="Return to Client Page" autofocus/>
   </form>
    ';
} else {
	echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
	echo "<br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
}

$conn->close();
}
?>

</body>
</html>