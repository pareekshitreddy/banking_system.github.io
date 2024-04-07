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
    if (isset($_POST["deleteAccountId"])) {
        $accountId = $conn->real_escape_string($_POST["deleteAccountId"]);

        // Fetch customer name for logging before deletion
        $nameResult = $conn->query("SELECT name FROM Customer WHERE customer_id = $accountId");
        $customerName = ($nameResult->num_rows > 0) ? $nameResult->fetch_assoc()['name'] : 'Unknown';

        // Start transaction
        $conn->begin_transaction();

        try {
            // Delete related entries from Card, Loan, Transaction tables
            $tables = ['Card', 'Loan', 'Transaction'];
            foreach ($tables as $table) {
                $sql = "DELETE FROM $table WHERE account_id = $accountId";
                if (!$conn->query($sql)) {
                    throw new Exception("Error deleting record in $table: " . $conn->error);
                }
            }

            // Logging before actual account deletion
            $logSql = "INSERT INTO Log (customer_name, operation) VALUES ('$customerName', 'Deleted')";
            if (!$conn->query($logSql)) {
                throw new Exception("Error creating log entry: " . $conn->error);
            }

            // Delete the account
            $sqlAccount = "DELETE FROM Account WHERE account_id = $accountId";
            if (!$conn->query($sqlAccount)) {
                throw new Exception("Error deleting account: " . $conn->error);
            }

            $conn->commit();
            echo "Account and related records deleted successfully!";

        } catch (Exception $e) {
            $conn->rollback();
            echo $e->getMessage();
        }
    }
}

// Close the database connection
$conn->close();
?>
