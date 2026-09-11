<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Your validation logic
    if (empty($username) || empty($password)) {
        // Display error message or redirect back to login page
        echo "Username and password are required!";
    } else {
        // Database connection parameters
        $host = "localhost"; // Hostname
        $db_username = "root"; // MySQL username
        $db_password = ""; // MySQL password
        $database = "user_accounts"; // Database name
        
        // Establish database connection
        $conn = mysqli_connect($host, $db_username, $db_password, $database);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Construct SQL query (vulnerable to SQL injection)
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        // Check if a user with the given username exists
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            // Verify password (plaintext, not secure)
            if ($password === $user['password']) {
                // Authentication successful
                $_SESSION["username"] = $username;
                header("Location: ../sqli_applied.html");
                exit;
            } else {
                // Invalid password
                echo "Invalid username or password!";
            }
        } else {
            // User not found
            echo "Invalid username or password!";
        }

        // Close connection
        mysqli_close($conn);
    }
}
?>
