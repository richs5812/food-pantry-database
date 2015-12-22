<?
$servername = "localhost";
$username = "root";
$password = "Brightmoor2015!";
$dbname = "BrightmoorConnect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully <br><br>";
?>