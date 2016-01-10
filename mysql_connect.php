<?
$servername = "localhost";

/* //prod setup
$username = "user1";
$password = "BrightmoorConnect2016!";
$dbname = "BrightmoorConnect";
*/

//QA setup
$username = "BrightmoorQA";
$password = "BrightmoorQA2016!";
$dbname = "QABrightmoorConnectQA";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully <br><br>";
?>