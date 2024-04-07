<?php

// Database connection
$servername = "localhost";
$username = "hgwoqkmy_ashwin";
$password = "ashwins1997";
$dbname = "hgwoqkmy_banksystem1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM TransactionReport";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>Report ID</th><th>Amount</th><th>From Account ID</th><th>To Account ID</th><th>Type</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["report_id"]."</td><td>".$row["amount"]."</td><td>".$row["from_account_id"]."</td><td>".$row["to_account_id"]."</td><td>".$row["type"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();

?>
