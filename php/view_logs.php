<?php
// Include your database connection code here
$servername = "localhost";
$username = "hgwoqkmy_ashwin";
$password = "ashwins1997";
$dbname = "hgwoqkmy_banksystem1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Log";
$result = $conn->query($sql);

$logs = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}


header('Content-Type: application/json');
echo json_encode($logs);

$conn->close();
?>
