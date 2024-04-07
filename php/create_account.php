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
    if (isset($_POST["customerName"])) {
        $customerName = $conn->real_escape_string($_POST["customerName"]);

        // Prepare and execute an SQL query to insert a new customer into the 'Customer' table
        $sqlCustomer = "INSERT INTO Customer (name) VALUES ('$customerName')";
        
        if ($conn->query($sqlCustomer) === TRUE) {
            $customerId = $conn->insert_id;

            // Assuming default values for account creation
            $defaultAccountType = 'Savings'; // Set a default account type
            $defaultBalance = 0.00; // Set a default balance

            // Insert a new account linked to the customer
            $sqlAccount = "INSERT INTO Account (customer_id, account_type, balance) VALUES ('$customerId', '$defaultAccountType', '$defaultBalance')";
            
            if ($conn->query($sqlAccount) === TRUE) {
                $accountId = $conn->insert_id;
                echo "Account created successfully! Account ID: $accountId";

                // Logging
                $logSql = "INSERT INTO Log (account_id, customer_name, operation) VALUES ('$accountId', '$customerName', 'Created')";
                
                if ($conn->query($logSql) === TRUE) {
                    echo "Log entry created successfully!";
                } else {
                    echo "Error creating log entry: " . $conn->error;
                }
            } else {
                echo "Error creating account: " . $conn->error;
            }
        } else {
            echo "Error creating customer: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
