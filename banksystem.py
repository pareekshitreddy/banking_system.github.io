import pymysql  # Assuming you are using MySQL and have the pymysql library installed

try:
    # Establish a database connection
    connection = pymysql.connect(host='localhost', user='root', password='Cosmicenergy24@', database='banksystem')
    print("Connected to the database.")
except pymysql.Error as e:
    print("Error connecting to the database:", e)

# Create a cursor object to interact with the database
cursor = connection.cursor()

'''------------------------Customer Functionality-------------------------------------------------------------'''
# Create operation
def create_customer(name):
    try:
        cursor.execute("INSERT INTO Customer (name) VALUES (%s)", (name,))
        connection.commit()
        print("Customer added successfully.")
    except pymysql.Error as e:
        print("Error adding customer:", e)

# Read operation
def get_customer_details(customer_id):
    cursor.execute("SELECT * FROM Customer WHERE customer_id = %s", (customer_id,))
    return cursor.fetchone()

# Update operation
def update_customer_name(customer_id, new_name):
    cursor.execute("UPDATE Customer SET name = %s WHERE customer_id = %s", (new_name, customer_id))
    connection.commit()

# Delete operation
def delete_customer(customer_id):
    cursor.execute("DELETE FROM Customer WHERE customer_id = %s", (customer_id,))
    connection.commit()

'''---------------------------------Card Functionality-----------------------------------------------------------'''
# Add update card type and delete the card
def create_card(customer_id):
    # Assuming a customer can have only one account for simplicity
    cursor.execute("SELECT account_id FROM Account WHERE customer_id = %s", (customer_id,))
    account_id = cursor.fetchone()[0] if cursor.rowcount > 0 else None

    if account_id:
        cursor.execute("INSERT INTO Card (account_id) VALUES (%s)", (account_id,))
        connection.commit()
        print(f"Card added for customer {customer_id} with account {account_id}")
    else:
        print(f"Customer {customer_id} does not have an associated account. Please create an account first.")

def update_card_type(account_id, new_card_type):
    cursor.execute("UPDATE Card SET card_type = %s WHERE account_id = %s", (new_card_type, account_id))
    connection.commit()

    # Print customer name, bank, account number, and updated card type
    cursor.execute("""
        SELECT C.name AS customer_name, B.name AS bank_name, A.account_id, Card.card_type
        FROM Customer C
        JOIN Account A ON C.customer_id = A.customer_id
        JOIN Bank B ON A.bank_id = B.bank_id
        JOIN Card ON A.account_id = Card.account_id
        WHERE A.account_id = %s
    """, (account_id,))
    card_details = cursor.fetchone()
    print(f"Card type updated for customer {card_details[0]} at {card_details[1]}, account {card_details[2]}:")
    print(f"Updated to {new_card_type} from {card_details[3]}")

def delete_card(account_id):
    cursor.execute("DELETE FROM Card WHERE account_id = %s", (account_id,))
    connection.commit()
    print(f"Card deleted for account {account_id}")


''' ----------------------------Employee Functionality-----------------------------------'''
# add create role
def create_employee(name, role_id, bank_id):
    cursor.execute("INSERT INTO Employee (name, role_id, bank_id) VALUES (%s, %s, %s)", (name, role_id, bank_id))
    connection.commit()

def appoint_as_manager(employee_id, bank_id):
    cursor.execute("""
        UPDATE Employee
        SET role_id = (SELECT role_id FROM EmployeeRole WHERE role_name = 'Manager'), bank_id = %s
        WHERE employee_id = %s
    """, (bank_id, employee_id))
    connection.commit()

    # Print employee and bank names
    cursor.execute("""
        SELECT E.name AS employee_name, B.name AS bank_name
        FROM Employee E
        JOIN Bank B ON E.bank_id = B.bank_id
        WHERE E.employee_id = %s
    """, (employee_id,))
    employee_details = cursor.fetchone()
    print(f"Employee {employee_details[0]} appointed as Manager for Bank {employee_details[1]}")

def demote_from_manager(employee_id):
    cursor.execute("""
        UPDATE Employee
        SET role_id = (SELECT role_id FROM EmployeeRole WHERE role_name = 'Employee'), bank_id = NULL
        WHERE employee_id = %s
    """, (employee_id,))
    connection.commit()

    # Print employee name
    cursor.execute("SELECT name FROM Employee WHERE employee_id = %s", (employee_id,))
    employee_name = cursor.fetchone()[0]
    print(f"Employee {employee_name} demoted from Manager")

'''----------------------Account functionality------------------------------------------'''
#Create Operation
def create_account(customer_id, bank_id, account_type, initial_balance):
    cursor.execute("INSERT INTO Account (customer_id, bank_id, account_type, balance) VALUES (%s, %s, %s, %s)",
                   (customer_id, bank_id, account_type, initial_balance))
    connection.commit()
    print(f"Account created for customer {customer_id}")

# Read operation
def get_account_balance(account_id):
    cursor.execute("SELECT balance FROM Account WHERE account_id = %s", (account_id,))
    return cursor.fetchone()[0] if cursor.rowcount > 0 else None

# Update operation
def deposit_to_account(account_id, amount):
    cursor.execute("UPDATE Account SET balance = balance + %s WHERE account_id = %s", (amount, account_id))
    connection.commit()
    print(f"Deposited {amount} to account {account_id}")

def withdraw_from_account(account_id, amount):
    current_balance = get_account_balance(account_id)
    if current_balance is not None and current_balance >= amount:
        cursor.execute("UPDATE Account SET balance = balance - %s WHERE account_id = %s", (amount, account_id))
        connection.commit()
        print(f"Withdrew {amount} from account {account_id}")
    else:
        print(f"Insufficient funds in account {account_id}")

# Read operation for account details
def get_account_details(account_id):
    cursor.execute("""
        SELECT A.account_id, C.name AS customer_name, B.name AS bank_name, A.account_type, A.balance
        FROM Account A
        JOIN Customer C ON A.customer_id = C.customer_id
        JOIN Bank B ON A.bank_id = B.bank_id
        WHERE A.account_id = %s
    """, (account_id,))
    return cursor.fetchone()

def delete_account(account_id):
    cursor.execute("DELETE FROM Account WHERE account_id = %s", (account_id,))
    connection.commit()
    print(f"Account {account_id} deleted")

'''------------------------------------------Loan Functionality------------------------------'''
def create_loan(customer_id, account_id, loan_type, amount):
    cursor.execute("INSERT INTO Loan (customer_id, account_id, loan_type, amount) VALUES (%s, %s, %s, %s)",
                   (customer_id, account_id, loan_type, amount))
    connection.commit()
    print(f"Loan created for customer {customer_id}")

# Read operation for loan details
def get_loan_details(loan_id):
    cursor.execute("""
        SELECT L.loan_id, C.name AS customer_name, A.account_id, A.account_type, L.loan_type, L.amount
        FROM Loan L
        JOIN Customer C ON L.customer_id = C.customer_id
        JOIN Account A ON L.account_id = A.account_id
        WHERE L.loan_id = %s
    """, (loan_id,))
    return cursor.fetchone()

def update_loan_amount(loan_id, new_amount):
    cursor.execute("UPDATE Loan SET amount = %s WHERE loan_id = %s", (new_amount, loan_id))
    connection.commit()
    print(f"Loan {loan_id} amount updated")


def delete_loan(loan_id):
    cursor.execute("DELETE FROM Loan WHERE loan_id = %s", (loan_id,))
    connection.commit()
    print(f"Loan {loan_id} deleted")

'''---------------------------------------Testing the functionaility---------------------------------------------'''
# Testing Customer Functionality
print("\n-------------- Testing Customer Functionality --------------")

# Create a customer
create_customer("John Doe")
john_details = get_customer_details(1)
print("Customer Details:", john_details)

# Update customer name
update_customer_name(1, "John Smith")
john_details_updated = get_customer_details(1)
print("Updated Customer Details:", john_details_updated)

# Delete customer
# delete_customer(1)
# deleted_john_details = get_customer_details(1)
# print("Deleted Customer Details:", deleted_john_details)


# Testing Employee Functionality
print("\n-------------- Testing Employee Functionality --------------")

# Create an employee
create_employee("Alice", 1, 1)

# Appoint as manager
appoint_as_manager(1, 1)

# Demote from manager
demote_from_manager(1)

# Testing Account Functionality
print("\n-------------- Testing Account Functionality --------------")

# Create an account
create_account(2, 1, "Savings", 1000.00)  # Assuming a customer with ID 2 and a bank with ID 1 exist

# Get account details
account_details = get_account_details(1)
print("\nAccount Details:")
print("Account ID:", account_details[0])
print("Customer Name:", account_details[1])
print("Bank Name:", account_details[2])
print("Account Type:", account_details[3])
print("Balance:", account_details[4])


# Deposit to account
deposit_to_account(1, 500.00)

# Withdraw from account
withdraw_from_account(1, 200.00)

# Delete account
#delete_account(1)

# Testing Loan Functionality
print("\n-------------- Testing Loan Functionality --------------")

# Create a loan
# Create a loan
create_loan(2, 2, "Personal", 5000.00)  # Assuming a customer with ID 2 and an account with ID 1 exist

# Get loan details
loan_details = get_loan_details(1)
print("\nLoan Details:")
print("Loan ID:", loan_details[0])
print("Customer Name:", loan_details[1])
print("Account ID:", loan_details[2])
print("Account Type:", loan_details[3])
print("Loan Type:", loan_details[4])
print("Loan Amount:", loan_details[5])

# Update loan amount
update_loan_amount(1, 6000.00)

# Delete loan
#delete_loan(1)

# Testing Card Functionality
print("\n-------------- Testing Card Functionality --------------")

# Create card for a customer
create_card(2)  # Assuming a customer with ID 2 exists

# Update card type
update_card_type(1, "Credit")

# Delete card
#delete_card(1)


# Close the cursor and connection
cursor.close()
connection.close()
