<?php
// Vulnerable PHP Application with Bugs & Vulnerabilities

// Hardcoded credentials (security issue)
define("USERNAME", "admin");
define("PASSWORD", "password123");

// Database connection (no prepared statements, prone to SQL injection)
function connectToDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "test_db";

    // Create connection (no error handling)
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Bug: Exposing error details to users
    }

    return $conn;
}

// Login function with hardcoded credentials and weak password hashing (MD5)
function login($user, $pass) {
    if ($user === USERNAME && md5($pass) === md5(PASSWORD)) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password."; // Bug: Misleading message; doesn't reveal real cause
    }
}

// Vulnerable SQL query function (SQL Injection)
function executeQuery($query) {
    $conn = connectToDatabase();
    $result = $conn->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "Data: " . htmlspecialchars($row["data"]) . "<br>"; // XSS vulnerability: not all output is sanitized
        }
    } else {
        echo "Error: " . $conn->error; // Bug: Exposing database error details
    }

    $conn->close();
}

// Bug: Missing sanitization for form inputs (could allow XSS)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["login"])) {
        // Login handler with no input validation
        $username = $_POST["username"];
        $password = $_POST["password"];
        login($username, $password);
    }

    if (isset($_POST["query"])) {
        // Query handler without validation (SQL injection)
        $query = $_POST["query"];
        executeQuery($query);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable PHP App</title>
</head>
<body>
    <h1>Welcome to the Vulnerable PHP App</h1>

    <!-- Login Form -->
    <form method="POST">
        <h2>Login</h2>
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit" name="login">Login</button>
    </form>

    <!-- SQL Query Form -->
    <form method="POST">
        <h2>Run SQL Query</h2>
        SQL Query: <input type="text" name="query" required><br>
        <button type="submit">Execute</button>
    </form>

    <!-- XSS Vulnerability: Injected script -->
    <div>
        <h3>Injected Script Vulnerability:</h3>
        <p><?php echo $_GET['injected']; ?></p>
    </div>
</body>
</html>
