import sqlite3
import os
import hashlib
from flask import Flask, request
import requests

# Example 1: SQL Injection Vulnerability
def sql_injection_example():
    user_input = "' OR 1=1 --"
    connection = sqlite3.connect('test.db')
    cursor = connection.cursor()

    # SQL Injection vulnerability: user input directly concatenated into query
    query = f"SELECT * FROM users WHERE username = '{user_input}'"
    cursor.execute(query)
    
    results = cursor.fetchall()
    for row in results:
        print(f"User: {row[0]}")
    
    connection.close()

# Example 2: Cross-Site Scripting (XSS)
app = Flask(__name__)

@app.route('/submit', methods=['GET'])
def xss_example():
    user_input = request.args.get('user_input')
    # Cross-Site Scripting (XSS): User input is reflected without sanitization
    return f"<h1>User Input: {user_input}</h1>"

# Example 3: Insecure File Handling (Reading sensitive file)
def insecure_file_handling_example():
    file_path = "/etc/passwd"  # Sensitive system file
    if os.path.exists(file_path):
        with open(file_path, 'r') as file:
            print(file.read())

# Example 4: Hardcoded Credentials
def hardcoded_credentials_example():
    username = "admin"  # Hardcoded username
    password = "password123"  # Hardcoded password

    if username == "admin" and password == "password123":
        print("Access granted")
    else:
        print("Access denied")

# Example 5: Insecure Hashing (MD5)
def insecure_hashing_example():
    password = "password123"
    # Vulnerable Hashing: MD5 is considered insecure
    hashed_password = hashlib.md5(password.encode()).hexdigest()
    print(f"Hashed password (MD5): {hashed_password}")

# Example 6: Insecure Deserialization
import pickle

def insecure_deserialization_example():
    serialized_data = pickle.dumps({"username": "admin", "password": "password123"})
    # Insecure deserialization: loading untrusted serialized data
    user_data = pickle.loads(serialized_data)
    print(user_data)

# Example 7: Insecure HTTP Request (No SSL/TLS)
def insecure_http_request_example():
    url = "http://example.com"  # HTTP request without SSL/TLS
    response = requests.get(url)
    print(response.text)

# Example 8: Directory Traversal Vulnerability
def directory_traversal_example():
    filename = request.args.get('filename')
    # Directory traversal vulnerability: the filename should be sanitized before opening
    with open(f"/var/www/{filename}", 'r') as file:
        print(file.read())

# Example 9: Unvalidated Redirects and Forwards
@app.route('/redirect', methods=['GET'])
def unvalidated_redirect_example():
    target_url = request.args.get('url')
    # Unvalidated Redirects: Directly redirecting without validating URL
    return redirect(target_url)

# Example 10: Open Redirect Vulnerability
@app.route('/login', methods=['GET'])
def open_redirect_example():
    redirect_to = request.args.get('next')
    # Open Redirect: Redirect to unvalidated external URL
    return redirect(redirect_to)

# Running all the examples
if __name__ == '__main__':
    sql_injection_example()
    app.run(debug=True)  # Run Flask app for XSS
    insecure_file_handling_example()
    hardcoded_credentials_example()
    insecure_hashing_example()
    insecure_deserialization_example()
    insecure_http_request_example()
    directory_traversal_example()
    open_redirect_example()
