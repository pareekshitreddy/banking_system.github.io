<?php
// Include your database connection code here
$servername = "localhost";
$username = "hgwoqkmy_ashwin";
$password = "ashwins1997";
$dbname = "hgwoqkmy_banksystem1";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve transaction data from the 'Transaction' table (you can customize this query)
$sql = "SELECT * FROM Transaction";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through the results and generate the report
    while ($row = $result->fetch_assoc()) {
        // Print or format the report as needed
        echo "Transaction ID: " . $row["transaction_id"] . ", Amount: " . $row["amount"] . "<br>";
    }
} else {
    echo "No transactions found.";
}

// Close the database connection
$conn->close();
?>
