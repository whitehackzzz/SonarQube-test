import sqlite3

# Hardcoded credentials (security issue)
USERNAME = "admin"
PASSWORD = "password123"

# Function to authenticate user
def authenticate_user(username, password):
    if username == USERNAME and password == PASSWORD:
        return True
    else:
        return False

# Function to execute a query (SQL Injection Vulnerability)
def execute_query(query):
    try:
        connection = sqlite3.connect("example.db")
        cursor = connection.cursor()
        # Directly executing user input query (unsafe practice)
        cursor.execute(query)
        connection.commit()
        print("Query executed successfully!")
    except Exception as e:
        # Catching broad exceptions (bad practice)
        print(f"An error occurred: {e}")
    finally:
        connection.close()

# Main function
def main():
    print("Welcome to the vulnerable app!")

    # Get user input for query (vulnerability point)
    query = input("Enter your SQL query: ")
    execute_query(query)

    # Authentication test
    username = input("Enter your username: ")
    password = input("Enter your password: ")
    if authenticate_user(username, password):
        print("Authentication successful!")
    else:
        print("Authentication failed!")

if __name__ == "__main__":
    main()
