<?php
//set timezone for strtotime php function to convert date to MySQL format from input format
date_default_timezone_set('America/Detroit');

//functions to create various form items
function checkBox($valueName, $labelName) {
	//reference global variable $row
	global $row;
    if ($row[$valueName]!='1'){
echo '<label for="'.$valueName.'">'.$labelName.': </label>
			<input type="hidden" name="'.$valueName.'" value="0" />
			<input type="checkbox" name="'.$valueName.'" id="'.$valueName.'" value="1" />';
} else {
	echo '<label for="'.$valueName.'">'.$labelName.': </label>
	<input type="hidden" name="'.$valueName.'" value="0" />
	<input type="checkbox" name="'.$valueName.'" id="'.$valueName.'" value="1" checked/>';
}	
}

function textInput($valueName, $labelName) {
	global $row;
	
	echo '<label for="'.$valueName.'">'.$labelName.': </label><input type="text" name="'.$valueName.'" id="'.$valueName.'" value="' .$row[$valueName].'"/>';	
}

function noLabelTextInput($valueName) {
	global $row;
	
	echo '<input type="text" name="'.$valueName.'" id="'.$valueName.'" value="' .$row[$valueName].'"/>';	
}

function numberInput($valueName, $labelName) {
	global $row;
	
	echo '<label for="'.$valueName.'">'.$labelName.': </label><input type="number" name="'.$valueName.'" id="'.$valueName.'" value="' .$row[$valueName].'" min="0" max="120"/>';	
}

function dateInput($valueName, $labelName) {
	global $row;
	
	//$originalDate = $row[$valueName];
	if ($row[$valueName]!=NULL){
	$displayDate = date("m-d-y", strtotime($row[$valueName]));
	} else {
		$displayDate = "  /  /  ";
	}
	/*	if ($row["ClientID"] == ""){
		$displayDate="  /  /  ";
		} else {
	$displayDate = "test";
	}*/

	echo '<label for="'.$valueName.'">'.$labelName.': </label><input type="text" name="'.$valueName.'" id="'.$valueName.'" value="' .$displayDate.'"/>';
}

function referralDateInput($valueName) {
	global $referralsRow;
	
	if ($referralsRow[$valueName]!=NULL){
	$displayDate = date("m/d/y", strtotime($referralsRow[$valueName]));
	}
		if ($referralsRow['ClientID'] == ""){
		$displayDate="  /  /  ";
		}

	echo '<input type="text" name="'.$valueName.'" id="'.$valueName.'" value="' .$displayDate.'"/>';
}

/*function noLabelDateInput($valueName) {
	global $row;
	
	if ($row[$valueName]!=NULL){
	$displayDate = date("m/d/y", strtotime($row[$valueName]));
	}
		if ($row[ClientID] == ""){
		$displayDate="  /  /  ";
		}

	echo '<input type="text" name="'.$valueName.'" id="'.$valueName.'" value="' .$displayDate.'"/>';
}*/

function emailInput($valueName, $labelName) {
	global $row;
	
	echo '<label for="'.$valueName.'">'.$labelName.': </label><input type="email" name="'.$valueName.'" id="'.$valueName.'" value="' .$row[$valueName] .'"/>';	
}

?>