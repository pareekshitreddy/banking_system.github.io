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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["inquiryAccountId"])) {
        $accountId = $_POST["inquiryAccountId"];

        // Retrieve the account balance from the 'Account' table
        $sql = "SELECT balance FROM Account WHERE account_id = $accountId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $balance = $row["balance"];
            echo "Account balance: $balance";
        } else {
            echo "Account not found!";
        }
    }
}

// Close the database connection
$conn->close();
?>