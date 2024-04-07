-- Create database
CREATE DATABASE IF NOT EXISTS banksystem;
USE banksystem;

-- Create tables
CREATE TABLE IF NOT EXISTS Customer (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    street VARCHAR(255),
    city VARCHAR(255),
    state VARCHAR(255),
    postal_code VARCHAR(20),
    country VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(20)

);

CREATE TABLE IF NOT EXISTS Bank (
    bank_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    established_year INT, -- Add the year the bank was established
    total_assets DECIMAL(15, 2) -- Add the total assets of the bank
);

CREATE TABLE IF NOT EXISTS Account (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    bank_id INT NOT NULL,
    account_type VARCHAR(50) NOT NULL,
    balance DECIMAL(15, 2) DEFAULT 0.00,
    open_date DATE, 
    last_transaction_date DATE,
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (bank_id) REFERENCES Bank(bank_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS EmployeeRole (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(255) NOT NULL,
    role_description TEXT

);

CREATE TABLE IF NOT EXISTS Employee (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    bank_id INT NOT NULL,
    hire_date DATE,
    salary DECIMAL(15, 2),
    FOREIGN KEY (role_id) REFERENCES EmployeeRole(role_id),
    FOREIGN KEY (bank_id) REFERENCES Bank(bank_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Card (
    card_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    card_type VARCHAR(50) NOT NULL,
    expiration_date DATE,
    FOREIGN KEY (account_id) REFERENCES Account(account_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Loan (
    loan_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    account_id INT NOT NULL,
    loan_type VARCHAR(50) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    interest_rate DECIMAL(5, 2),
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (account_id) REFERENCES Account(account_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Transaction (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    transaction_date DATETIME,
    FOREIGN KEY (account_id) REFERENCES Account(account_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS TransactionReport (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT,
    amount DECIMAL(15, 2),
    from_account_id INT,
    to_account_id INT,
    type ENUM('deposit', 'withdrawal', 'transfer'),
    FOREIGN KEY (transaction_id) REFERENCES Transaction(transaction_id)
);

CREATE TABLE IF NOT EXISTS AccountSummary (
    summary_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT,
    customer_name VARCHAR(255),
    action_amount DECIMAL(15, 2),
    balance_after DECIMAL(15, 2),
    action_type ENUM('credited', 'debited'),
    FOREIGN KEY (account_id) REFERENCES Account(account_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- LoginDetails table with cascade rules
CREATE TABLE IF NOT EXISTS LoginDetails (
    login_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    password VARCHAR(255),
    employee_id INT,
    FOREIGN KEY (employee_id) REFERENCES Employee(employee_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Log table with cascade rules
CREATE TABLE IF NOT EXISTS Log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT,
    customer_name VARCHAR(255),
    operation VARCHAR(50),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (account_id) REFERENCES Account(account_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insert random data into Customer table
INSERT INTO Customer (name, street, city, state, postal_code, country, email, phone)
VALUES
    ('John Doe', '123 Main St', 'Cityville', 'CA', '12345', 'USA', 'john.doe@email.com', '555-1234'),
    ('Jane Smith', '456 Oak St', 'Townsville', 'NY', '67890', 'USA', 'jane.smith@email.com', '555-5678'),
    ('Robert Johnson', '789 Pine St', 'Villagetown', 'NJ', '45678', 'USA', 'robert.johnson@email.com', '555-9876'),
    ('Alice Williams', '101 Maple St', 'Hamletville', 'TX', '98765', 'USA', 'alice.williams@email.com', '555-4321'),
    ('Michael Brown', '202 Elm St', 'Boroughtown', 'FL', '54321', 'USA', 'michael.brown@email.com', '555-8765');

-- Insert random data into Bank table
INSERT INTO Bank (name, location)
VALUES
    ('Bank of America', 'boston'),
    ('Bank of America', 'NY'),
    ('Bank of America', 'NJ'),
    ('Bank of America', 'RhodeIsland'),
    ('Bank of America', 'Vermont');

-- Insert random data into EmployeeRole table
INSERT INTO EmployeeRole (role_name)
VALUES
    ('Manager'),
    ('Employee');

-- Insert random data into Employee table
INSERT INTO Employee (name, role_id, bank_id)
VALUES
    ('Eva Davis', 1, 1),
    ('Samuel Johnson', 2, 2),
    ('Linda White', 2, 1),
    ('Andrew Miller', 2, 3),
    ('Emily Wilson', 2, 4);

-- Insert random data into Account table
INSERT INTO Account (customer_id, bank_id, account_type, balance, open_date, last_transaction_date)
VALUES
    (1, 1, 'Savings', 1500.00, '2023-01-01', '2023-01-10'),
    (2, 2, 'Checking', 2500.50, '2023-02-01', '2023-02-15'),
    (3, 3, 'Savings', 10000.00, '2023-03-01', '2023-03-20'),
    (4, 4, 'Checking', 500.00, '2023-04-01', '2023-04-25'),
    (5, 5, 'Savings', 7500.75, '2023-05-01', '2023-05-30');

-- Insert random data into Card table
INSERT INTO Card (account_id, card_type, expiration_date)
VALUES
    (1, 'Debit', '2024-01-01'),
    (2, 'Credit', '2024-02-01'),
    (3, 'Debit', '2024-03-01'),
    (4, 'Credit', '2024-04-01'),
    (5, 'Debit', '2024-05-01');

-- Insert random data into Loan table with realistic values
INSERT INTO Loan (customer_id, account_id, loan_type, amount, interest_rate)
VALUES
    (1, 1, 'Personal', 5000.00, 5.0),
    (2, 2, 'Home', 100000.00, 3.5),
    (3, 3, 'Personal', 7500.50, 4.2);

-- Insert random data into Transaction table with realistic values
INSERT INTO Transaction (account_id, amount, transaction_date)
VALUES
    (1, -200.00, '2023-01-05 10:30:00'),
    (2, 50.00, '2023-02-10 15:45:00'),
    (3, -1000.00, '2023-03-15 12:00:00'),
    (4, 20.50, '2023-04-20 08:30:00'),
    (5, -300.75, '2023-05-25 17:15:00');



