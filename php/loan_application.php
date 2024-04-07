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
    if (isset($_POST["applicantName"]) && isset($_POST["loanAmount"])) {
        $applicantName = $_POST["applicantName"];
        $loanAmount = $_POST["loanAmount"];

        // Insert loan application into the 'Loan' table
        $sql = "INSERT INTO Loan (customer_id, loan_type, amount) VALUES (1, 'Personal Loan', $loanAmount)";
        
        if ($conn->query($sql) === TRUE) {
            echo "Loan application submitted successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
