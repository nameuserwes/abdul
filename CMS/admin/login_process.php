<?php
session_start();
include_once 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        
        $_SESSION['username'] = $username;
        // Redirect to dashboard or any other authenticated page
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid username or password";
    }
} else {
    echo "Invalid username or password";
}

$conn->close();
?>
