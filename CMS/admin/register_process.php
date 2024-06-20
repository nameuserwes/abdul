<?php
include_once 'db.php';

$username = $_POST['username'];
$email = $_POST['email'];
$psw = $_POST['password'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password,psw) VALUES ('$username', '$email', '$password','$psw')";

if ($conn->query($sql) === TRUE) {
    // Redirect to login page
    header("Location: login.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

