<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

function updateAccountBalance($conn, $accountId, $amount) {
    $sql = "UPDATE Account SET balance = balance + ($amount) WHERE account_id = $accountId";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error updating account: " . $conn->error;
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['operation'])) {
        $operation = $_POST['operation'];

        switch($operation) {
            case 'deposit':
                if (isset($_POST["depositAccountId"]) && isset($_POST["depositAmount"])) {
                    $accountId = $_POST["depositAccountId"];
                    $amount = $_POST["depositAmount"];
                    if (updateAccountBalance($conn, $accountId, $amount)) {
                        echo "Deposit successful";
                    }
                }
                break;

            case 'withdraw':
                if (isset($_POST["withdrawalAccountId"]) && isset($_POST["withdrawalAmount"])) {
                    $accountId = $_POST["withdrawalAccountId"];
                    $amount = -$_POST["withdrawalAmount"]; // Negative for withdrawal
                    if (updateAccountBalance($conn, $accountId, $amount)) {
                        echo "Withdrawal successful";
                    }
                }
                break;

            case 'transfer':
                if (isset($_POST["fromAccountId"]) && isset($_POST["toAccountId"]) && isset($_POST["transferAmount"])) {
                    $fromAccountId = $_POST["fromAccountId"];
                    $toAccountId = $_POST["toAccountId"];
                    $amount = $_POST["transferAmount"];

                    $conn->begin_transaction();
                    if (updateAccountBalance($conn, $fromAccountId, -$amount) && updateAccountBalance($conn, $toAccountId, $amount)) {
                        $conn->commit();
                        echo "Transfer successful";
                    } else {
                        $conn->rollback();
                        echo "Transfer failed";
                    }
                }
                break;

            default:
                echo "Invalid operation";
        }
    }
}

// Close the database connection
$conn->close();
?>
