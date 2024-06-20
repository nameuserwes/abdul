<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];

    // Query untuk menyimpan data penulis baru ke database
    $sql = "INSERT INTO penulis (nama) VALUES ('$nama')";

    if ($conn->query($sql) === TRUE) {
        header("Location: penulis.php"); // Redirect ke halaman penulis setelah berhasil menambah penulis
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    header("Location: penulis.php"); // Redirect ke halaman penulis jika tidak ada data POST
    exit();
}
?>
