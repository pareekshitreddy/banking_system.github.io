<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "hgwoqkmy_ashwin";
$password = "ashwins1997";
$dbname = "hgwoqkmy_banksystem1";

$conn = new mysqli($servername, $username, $password, $dbname);

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

function getCurrentBalance($conn, $accountId) {
    $sql = "SELECT balance FROM Account WHERE account_id = $accountId";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['balance'];
    } else {
        return 0; // Handle error appropriately
    }
}

function insertTransactionReport($conn, $amount, $fromAccountId, $toAccountId, $type) {
    $sql = "INSERT INTO TransactionReport (amount, from_account_id, to_account_id, type) VALUES ($amount, $fromAccountId, $toAccountId, '$type')";
    $conn->query($sql);
}

function insertAccountSummary($conn, $accountId, $amount, $balanceAfter, $type) {
    $result = $conn->query("SELECT name FROM Customer WHERE customer_id = (SELECT customer_id FROM Account WHERE account_id = $accountId)");
    $customerName = ($result->num_rows > 0) ? $result->fetch_assoc()['name'] : 'Unknown';

    $sql = "INSERT INTO AccountSummary (account_id, customer_name, action_amount, balance_after, action_type) VALUES ($accountId, '$customerName', $amount, $balanceAfter, '$type')";
    $conn->query($sql);
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
                        insertTransactionReport($conn, $amount, -1 , $accountId, 'deposit');
                        insertAccountSummary($conn, $accountId, $amount, getCurrentBalance($conn, $accountId), 'credited');
                    }
                }
                break;

            case 'withdraw':
                if (isset($_POST["withdrawalAccountId"]) && isset($_POST["withdrawalAmount"])) {
                    $accountId = $_POST["withdrawalAccountId"];
                    $amount = $_POST["withdrawalAmount"];
                    if (updateAccountBalance($conn, $accountId, -$amount)) {
                        echo "Withdrawal successful";
                        insertTransactionReport($conn, $amount, $accountId, -1, 'withdrawal');
                        insertAccountSummary($conn, $accountId, $amount, getCurrentBalance($conn, $accountId), 'debited');
                    }
                }
                break;

                
                case 'transfer':
                    if (isset($_POST["fromAccountId"]) && isset($_POST["toAccountId"]) && isset($_POST["transferAmount"])) {
                        $fromAccountId = $_POST["fromAccountId"];
                        $toAccountId = $_POST["toAccountId"];
                        $amount = $_POST["transferAmount"];
    
                        // Begin transaction
                        $conn->begin_transaction();
    
                        if (updateAccountBalance($conn, $fromAccountId, -$amount) && updateAccountBalance($conn, $toAccountId, $amount)) {
                            $fromAccountBalanceAfter = getCurrentBalance($conn, $fromAccountId);
                            $toAccountBalanceAfter = getCurrentBalance($conn, $toAccountId);
    
                            
                            $conn->commit();
                            echo "Transfer successful";

                            insertTransactionReport($conn, $amount, $fromAccountId, $toAccountId, 'transfer');
                            insertAccountSummary($conn, $fromAccountId, -$amount, $fromAccountBalanceAfter, 'debited');
                            insertAccountSummary($conn, $toAccountId, $amount, $toAccountBalanceAfter, 'credited');
    
                        } else {
                            $conn->rollback();
                            echo "Error during transfer";
                        }
                    }
                    break;
    
                default:
                    echo "Invalid operation";
            }
        }
    }
    
    $conn->close();
?>