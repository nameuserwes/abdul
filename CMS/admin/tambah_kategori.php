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

    // Query untuk menyimpan data kategori baru ke database
    $sql = "INSERT INTO kategori (nama) VALUES ('$nama')";

    if ($conn->query($sql) === TRUE) {
        header("Location: kategori.php"); // Redirect ke halaman kategori setelah berhasil menambah kategori
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    header("Location: kategori.php"); // Redirect ke halaman kategori jika tidak ada data POST
    exit();
}
?>
