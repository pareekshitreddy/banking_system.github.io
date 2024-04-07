<?php
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
    if (isset($_POST["accountId"]) && isset($_POST["newCustomerName"])) {
        $accountId = $conn->real_escape_string($_POST["accountId"]);
        $newCustomerName = $conn->real_escape_string($_POST["newCustomerName"]);

        // Start transaction
        $conn->begin_transaction();

        try {
            // Update the customer's name
            $sql = "UPDATE Customer SET name = '$newCustomerName' WHERE customer_id = $accountId";

            if (!$conn->query($sql)) {
                throw new Exception("Error updating account: " . $conn->error);
            }

            // Log the update
            $logSql = "INSERT INTO Log (account_id, customer_name, operation) VALUES ('$accountId', '$newCustomerName', 'Updated')";

            if (!$conn->query($logSql)) {
                throw new Exception("Error logging the update: " . $conn->error);
            }

            // Commit the transaction
            $conn->commit();
            echo "Account updated successfully! Log entry created.";

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            echo $e->getMessage();
        }
    }
}

// Close the database connection
$conn->close();
?>
