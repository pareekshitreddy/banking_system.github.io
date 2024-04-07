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

$sql = "SELECT * FROM AccountSummary";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>Summary ID</th><th>Account ID</th><th>Customer Name</th><th>Action Amount</th><th>Balance After</th><th>Action Type</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["summary_id"]."</td><td>".$row["account_id"]."</td><td>".$row["customer_name"]."</td><td>".$row["action_amount"]."</td><td>".$row["balance_after"]."</td><td>".$row["action_type"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();

?>
