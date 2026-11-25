<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myWebsite";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Abone olduğunuz için teşekkürler. Onay e-postası gönderimi simüle edilir; gerçek e-posta gönderilmez.";
        } else {
            echo "Üzgünüz, aboneliğiniz işlenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.";
        }

        $stmt->close();
    } else {
        echo "Lütfen geçerli bir e-posta adresi girin.";
    }
} else {
    echo "Lütfen formu geçerli bir e-posta adresi ile gönderin.";
}

$conn->close();
?>
