<?php
// Vulnerable PHP Application with Bugs & Vulnerabilities

// 1. Hardcoded credentials (security issue)
define("USERNAME", "admin");
define("PASSWORD", "password123");

// 2. Using weak hashing algorithm (MD5) for passwords
define("USER_HASHED_PASSWORD", md5("password123")); // Hardcoded MD5 hashed password

// Database connection (no prepared statements, prone to SQL injection)
function connectToDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "test_db";

    // 3. Exposing sensitive database error details
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Bug: Exposing error details
    }

    return $conn;
}

// 4. Login function with hardcoded credentials and weak password hashing (MD5)
function login($user, $pass) {
    if ($user === USERNAME && md5($pass) === USER_HASHED_PASSWORD) {
        echo "Login successful!";
    } else {
        echo "Invalid username or password."; // Bug: Misleading message; doesn't reveal real cause
    }
}

// 5. Vulnerable SQL query function (SQL Injection)
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

// 6. Insecure session management (no session timeout)
session_start();
if (!isset($_SESSION['logged_in'])) {
    $_SESSION['logged_in'] = false;
}

// 7. Missing input validation and sanitization (vulnerable to XSS)
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

// 8. No CSRF protection in forms
// 9. Exposed session ID in URL (cookie not used)
echo "Session ID: " . session_id() . "<br>";

// 10. No HTTPS enforced (vulnerable to man-in-the-middle attacks)
echo "Ensure you're using HTTPS<br>";

// 11. Hardcoded sensitive data in source code (e.g., database credentials)
$apiKey = "hardcoded_api_key";

// 12. Using `eval()` with user input (Remote Code Execution vulnerability)
if (isset($_POST["code"])) {
    eval($_POST["code"]);
}

// 13. Insecure file upload handling (allows arbitrary files to be uploaded)
if (isset($_FILES["file_upload"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file_upload"]["name"]);
    move_uploaded_file($_FILES["file_upload"]["tmp_name"], $target_file);
}

// 14. Directory traversal vulnerability (arbitrary file access)
$file = $_GET["file"];
$file_contents = file_get_contents($file); // Directory traversal if user controls the file path
echo $file_contents;

// 15. Insufficient password strength validation
$password = $_POST["password"];
if (strlen($password) < 8) {
    echo "Weak password, should be at least 8 characters.";
}

// 16. Information Disclosure: Exposing PHP errors to the user
ini_set('display_errors', 1); // Exposing detailed PHP error messages

// 17. Lack of secure cookie settings
setcookie("session", "value"); // Cookie isn't marked as secure or HttpOnly

// 18. SQL Injection in direct database interaction
$id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = $id"; // No sanitization or prepared statements
executeQuery($query);

// 19. No logging for failed login attempts (brute force vulnerability)
if ($_POST['username'] !== USERNAME || $_POST['password'] !== PASSWORD) {
    echo "Login failed"; // No logging for failed login attempts to prevent brute force attacks
}

// 20. Exposing sensitive user data (e.g., password in plain text)
echo "Password: " . PASSWORD . "<br>"; // Hardcoded password exposed to the user
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable PHP App</title>
</head>
<body>
    <h1>Welcome to the Vulnerable PHP App</h1>

    <!-- 21. Login Form (with no input validation) -->
    <form method="POST">
        <h2>Login</h2>
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit" name="login">Login</button>
    </form>

    <!-- 22. SQL Query Form -->
    <form method="POST">
        <h2>Run SQL Query</h2>
        SQL Query: <input type="text" name="query" required><br>
        <button type="submit">Execute</button>
    </form>

    <!-- 23. File Upload Form (Insecure) -->
    <form method="POST" enctype="multipart/form-data">
        <h2>Upload File</h2>
        Select file: <input type="file" name="file_upload" required><br>
        <button type="submit">Upload</button>
    </form>

    <!-- 24. Injected XSS Vulnerability: -->
    <div>
        <h3>Injected Script Vulnerability:</h3>
        <p><?php echo $_GET['injected']; ?></p>
    </div>

    <!-- 25. Directory Traversal -->
    <form method="GET">
        <h2>Read File</h2>
        File path: <input type="text" name="file" required><br>
        <button type="submit">Read</button>
    </form>

    <!-- 26. Insecure Eval Execution -->
    <form method="POST">
        <h2>Execute Code (Insecure Eval)</h2>
        PHP Code: <input type="text" name="code" required><br>
        <button type="submit">Execute</button>
    </form>
</body>
</html>
