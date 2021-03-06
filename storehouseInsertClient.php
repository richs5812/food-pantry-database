<?php

//code for session variable to make sure user is logged in
if(empty($_SESSION['username'])) {
header('Location: login.php');
exit;
}

//set timezone for strtotime php function to convert date to MySQL format from input format
date_default_timezone_set('America/Detroit');

//format dates for MySQL from input format and assign NULL if no input
if($_POST['EnrollmentDate']!=NULL){
$sqlFormattedEnrollmentDate = date("Y-m-d", strtotime($_POST['EnrollmentDate']));
} else {
	$sqlFormattedEnrollmentDate = NULL;
	}
if($_POST['CoatOrderDate']!=NULL){
$sqlFormattedCoatOrderDate = date("Y-m-d", strtotime($_POST['CoatOrderDate']));
} else {
	$sqlFormattedCoatOrderDate = NULL;
	}
	
if(!isset($_POST['HasStove'])){
	$_POST['HasStove'] = NULL;
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
/*
 if (empty($_POST["FirstName"])) {
    $firstName = NULL;
  } else {
	 	$firstName = test_input($_POST["FirstName"]);
   	 	// check if name only contains letters and whitespace
   	 	if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
   			echo "Only letters and white space allowed. You entered ".$firstName." Your entry for this field was discarded.<br>";
			$firstName = NULL;
    }
}*/

//create array of form values
$posts = array($_POST['FirstName'],$_POST['LastName'],$_POST['Address'],$_POST['Address2'],$_POST['HomePhoneNumber'],$_POST['ZipCode'],$_POST['Age'],$_POST['Gender'],$_POST['Pregnant'],$sqlFormattedEnrollmentDate,$_POST['AddressVerified'],$_POST['EmailAddress'],$_POST['CellPhoneNumber'],$_POST['FamilySize'],$_POST['AdultsNumber'],$_POST['AgeRange05'],$_POST['AgeRange617'],$_POST['AgeRange1829'],$_POST['AgeRange3039'],$_POST['AgeRange4049'],$_POST['AgeRange5064'],$_POST['AgeRange65'],$_POST['StateEmergencyRelease'],$_POST['FoodStampAssistance'],$_POST['LimitedHealthServicesReferral'],$_POST['AdditionalServices'],$_POST['OtherNotes'],$_POST['CoatOrder'],$_POST['PreviousChristmasFoodYes'],$_POST['PreviousChristmasFoodNo'],$sqlFormattedCoatOrderDate,$_POST['ChildrenNumber'],$_POST['HasStove'],$_POST['ChildcareServices'],$_POST['HeatShutoff'],$_POST['LightShutoff'],$_POST['WaterShutoff'],$_POST['OtherShutoff'],$_POST['TaxesDifficulty'],$_POST['ForeclosureNotice'],$_POST['LandlordEviction'],$_POST['OtherHousingIssue']);

//create array to place post or NULL values
$fieldArray = array();

//loop through each post value and assign NULL if blank
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

$stmt = $conn->prepare("INSERT INTO Clients (FirstName, LastName, Address, Address2, HomePhoneNumber, ZipCode, Age, Gender, Pregnant, EnrollmentDate, AddressVerified, EmailAddress, CellPhoneNumber, FamilySize, AdultsNumber, AgeRange05, AgeRange617, AgeRange1829, AgeRange3039, AgeRange4049, AgeRange5064, AgeRange65, StateEmergencyRelease, FoodStampAssistance, LimitedHealthServicesReferral, AdditionalServices, OtherNotes, CoatOrder, PreviousChristmasFoodYes, PreviousChristmasFoodNo, CoatOrderDate, ChildrenNumber, HasStove, ChildcareServices, HeatShutoff, LightShutoff, WaterShutoff, OtherShutoff, TaxesDifficulty, ForeclosureNotice, LandlordEviction, OtherHousingIssue) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('ssssssssssssssssssssssssssssssssssssssssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4], $fieldArray[5], $fieldArray[6], $fieldArray[7], $fieldArray[8], $fieldArray[9], $fieldArray[10], $fieldArray[11], $fieldArray[12], $fieldArray[13], $fieldArray[14], $fieldArray[15], $fieldArray[16], $fieldArray[17], $fieldArray[18], $fieldArray[19], $fieldArray[20], $fieldArray[21], $fieldArray[22], $fieldArray[23], $fieldArray[24], $fieldArray[25], $fieldArray[26], $fieldArray[27], $fieldArray[28], $fieldArray[29], $fieldArray[30], $fieldArray[31], $fieldArray[32], $fieldArray[33], $fieldArray[34], $fieldArray[35], $fieldArray[36], $fieldArray[37], $fieldArray[38], $fieldArray[39], $fieldArray[40], $fieldArray[41]);

if ($stmt->execute() == TRUE) {
	//select clientID that was just created
  /*$sql = "SELECT ClientID FROM Clients WHERE FirstName='$_POST[FirstName]' AND LastName='$_POST[LastName]' AND Address='$_POST[Address]'";
  $Result = $conn->query($sql);
  $ResultsRow = $Result->fetch_assoc();*/
  
	$returnButtonStmt = $conn->prepare("SELECT ClientID FROM Clients WHERE FirstName=? AND LastName=? AND Address=?");
	$returnButtonStmt->bind_param('sss', $_POST['FirstName'],$_POST['LastName'],$_POST['Address']);
	$returnButtonStmt->execute();
	$returnButtonResult = $returnButtonStmt->get_result();
	//$ResultsRow = $returnButtonResult->fetch_assoc();

	if ($returnButtonResult->num_rows > 0) {
    while($ResultsRow = $returnButtonResult->fetch_assoc()){
	echo 'New client record created successfully.<br><br>
	<form action="storehousePantry.php" method="post">
    <input type="hidden" name="ClientID" value="' .$ResultsRow['ClientID']. '" />
    <input type="submit" value="Return to Client Page" autofocus/>
   </form>
    ';
    }
    } else {
  echo "New client record created successfully.<br><br> <a href=\"storehousePantry.php\">Return to database</a>";
 }
} else {
	echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
	echo "<br><br> <a href=\"storehousePantry.php\">Return to database</a>";
}

?>