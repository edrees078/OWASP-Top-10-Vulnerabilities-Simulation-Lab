<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    if (!empty($name)) {
        $_SESSION['greeting'] = "Hello, $name!";
    } else {
        $_SESSION['greeting'] = "Hello, anonymous!";
    }
}

header("Location: Vulnerabilities/XSS.php"); // Redirect back to the form page
exit();
?>
