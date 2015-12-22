<!DOCTYPE html>
<html>
<head>
<title>Brightmoor Connection Database</title>
</head>
<body>


<h1>Enter new family member</h1>
<?php require_once ('nav.html'); ?>
<form id="dropDownMenu" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">

<!--drop down menu-->
<select name="ClientID" onchange="change()">

<?php 
require_once ('mysql_connect.php');

$sql = "SELECT ClientID, FirstName, LastName FROM Clients ORDER BY LastName ASC, FirstName DESC;";
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

<form action="FamilyMemberInsert.php" method="post">
<input type="hidden" name="ClientID" value="<?php echo $_POST[ClientID];?>" />
<input type="hidden" name="FirstName" value="<?php echo $_POST[FirstName];?>" />
<input type="hidden" name="LastName" value="<?php echo $_POST[LastName];?>" />
<?php echo "ClientID: $_POST[ClientID]";?><br>
FamilyMemberName: <input type="text" name="FamilyMemberName" /><br><br>
Age: <input type="number" name="FamilyMemberAge" /><br><br>
Gender: <select name="FamilyMemberGender">
  			<option value=""></option>
 			 <option value="F">F</option>
 			 <option value="M">M</option>
		</select><br><br>
Relationship: <input type="text" name="Relationship" /><br><br>
<input type="submit" />
</form>

</body>
</html>