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