<?php

//connect to database using php script
//require_once ('mysql_connect.php');
date_default_timezone_set('America/Detroit');

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
/*
$posts = array($_POST[FirstName],$_POST[LastName],$_POST[Address],$_POST[HomePhoneNumber],$_POST[ZipCode],$_POST[Age],$_POST[Gender],$_POST[Pregnant],$sqlFormattedEnrollmentDate,$_POST[AddressVerified],$_POST[EmailAddress],$_POST[CellPhoneNumber],$_POST[FamilySize],$_POST[AdultsNumber],$_POST[AgeRange05],$_POST[AgeRange617],$_POST[AgeRange1829],$_POST[AgeRange3039],$_POST[AgeRange4049],$_POST[AgeRange5064],$_POST[AgeRange65],$_POST[StoveYes],$_POST[StoveNo],$_POST[StateEmergencyRelease],$_POST[FoodStampAssistance],$_POST[LimitedHealthServicesReferral],$_POST[AdditionalServices],$_POST[OtherNotes],$_POST[CoatOrder],$_POST[PreviousChristmasFoodYes],$_POST[PreviousChristmasFoodNo],$sqlFormattedCoatOrderDate,$_POST[ChildrenNumber]);

$arrlength = count($posts);

for($x = 0; $x < $arrlength; $x++) {
    if ($posts[$x]==NULL){
    $posts[$x]==NULL;
    }
}

echo "test $posts[5]";
echo '<br> actual value: '.$_POST[Age].'';*/

/*
$sql="INSERT INTO Clients (FirstName, LastName, Address, HomePhoneNumber, ZipCode, Age, Gender, Pregnant, EnrollmentDate, AddressVerified, EmailAddress, CellPhoneNumber, FamilySize, AdultsNumber, AgeRange05, AgeRange617, AgeRange1829, AgeRange3039, AgeRange4049, AgeRange5064, AgeRange65, StoveYes, StoveNo, StateEmergencyRelease, FoodStampAssistance, LimitedHealthServicesReferral, AdditionalServices, OtherNotes, CoatOrder, PreviousChristmasFoodYes, PreviousChristmasFoodNo, CoatOrderDate, ChildrenNumber)

VALUES
('$_POST[FirstName]','$lastName','$_POST[Address]','$_POST[HomePhoneNumber]','$_POST[ZipCode]','$_POST[Age]','$_POST[Gender]','$_POST[Pregnant]','$sqlFormattedEnrollmentDate','$_POST[AddressVerified]','$_POST[EmailAddress]','$_POST[CellPhoneNumber]','$_POST[FamilySize]','$_POST[AdultsNumber]','$_POST[AgeRange05]','$_POST[AgeRange617]','$_POST[AgeRange1829]','$_POST[AgeRange3039]','$_POST[AgeRange4049]','$_POST[AgeRange5064]','$_POST[AgeRange65]','$_POST[StoveYes]','$_POST[StoveNo]','$_POST[StateEmergencyRelease]','$_POST[FoodStampAssistance]','$_POST[LimitedHealthServicesReferral]','$_POST[AdditionalServices]','$_POST[OtherNotes]','$_POST[CoatOrder]','$_POST[PreviousChristmasFoodYes]','$_POST[PreviousChristmasFoodNo]','$sqlFormattedCoatOrderDate','$_POST[ChildrenNumber]')";*/

/*
$lastName = ($_POST[LastName] != '') ? $_POST[LastName] : NULL;
$clientAge = ($_POST[Age] != '') ? $_POST[Age] : NULL;

$field1 = $_POST[FirstName];
$field2 = $lastName;
$field3 = $clientAge;
$field4 = $sqlFormattedEnrollmentDate;
*/

$posts = array($_POST[FirstName],$_POST[LastName],$_POST[Address],$_POST[Address2],$_POST[HomePhoneNumber],$_POST[ZipCode],$_POST[Age],$_POST[Gender],$_POST[Pregnant],$sqlFormattedEnrollmentDate,$_POST[AddressVerified],$_POST[EmailAddress],$_POST[CellPhoneNumber],$_POST[FamilySize],$_POST[AdultsNumber],$_POST[AgeRange05],$_POST[AgeRange617],$_POST[AgeRange1829],$_POST[AgeRange3039],$_POST[AgeRange4049],$_POST[AgeRange5064],$_POST[AgeRange65],$_POST[StoveYes],$_POST[StoveNo],$_POST[StateEmergencyRelease],$_POST[FoodStampAssistance],$_POST[LimitedHealthServicesReferral],$_POST[AdditionalServices],$_POST[OtherNotes],$_POST[CoatOrder],$_POST[PreviousChristmasFoodYes],$_POST[PreviousChristmasFoodNo],$sqlFormattedCoatOrderDate,$_POST[ChildrenNumber]);

$fieldArray = array();

$arrlength = count($posts);
//echo $arrlength;
for($x = 0; $x < $arrlength; $x++) {
    $fieldArray[$x] = ($posts[$x] != '') ? $posts[$x] : NULL;
}
/*
for($x = 0; $x < $arrlength; $x++) {
   	echo '<br>';
    echo $fieldArray[$x];
}*/

$stmt = $conn->prepare("INSERT INTO Clients (FirstName, LastName, Address, Address2, HomePhoneNumber, ZipCode, Age, Gender, Pregnant, EnrollmentDate, AddressVerified, EmailAddress, CellPhoneNumber, FamilySize, AdultsNumber, AgeRange05, AgeRange617, AgeRange1829, AgeRange3039, AgeRange4049, AgeRange5064, AgeRange65, StoveYes, StoveNo, StateEmergencyRelease, FoodStampAssistance, LimitedHealthServicesReferral, AdditionalServices, OtherNotes, CoatOrder, PreviousChristmasFoodYes, PreviousChristmasFoodNo, CoatOrderDate, ChildrenNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('ssssssssssssssssssssssssssssssssss', $fieldArray[0], $fieldArray[1], $fieldArray[2], $fieldArray[3], $fieldArray[4], $fieldArray[5], $fieldArray[6], $fieldArray[7], $fieldArray[8], $sqlFormattedEnrollmentDate, $fieldArray[10], $fieldArray[11], $fieldArray[12], $fieldArray[13], $fieldArray[14], $fieldArray[15], $fieldArray[16], $fieldArray[17], $fieldArray[18], $fieldArray[19], $fieldArray[20], $fieldArray[21], $fieldArray[22], $fieldArray[23], $fieldArray[24], $fieldArray[25], $fieldArray[26], $fieldArray[27], $fieldArray[28], $fieldArray[29], $fieldArray[30], $fieldArray[31], $sqlFormattedCoatOrderDate, $fieldArray[33]);

//$result = $stmt->execute();
if ($stmt->execute() == TRUE) {
  echo  "New record created successfully";
  echo "<br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
} else {
	echo "Error: ' . $sql . ' <br> '. $stmt->error.'";
	echo "<br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
}



//echo "New record created. <br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";

/*
if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully. <br><br>";
    //require_once ('nav.html');
    echo "New record created successfully. <br><br> <a href=\"brightmoorPantry.php\">Return to database</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}*/

//$conn->close();

?>