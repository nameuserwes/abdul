<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include_once 'db.php';

$username = $_SESSION['username']; // Dapatkan nama pengguna dari session

// Periksa apakah parameter ID kategori dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus kategori dari database
    $sql = "DELETE FROM kategori WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect kembali ke halaman kategori setelah berhasil menghapus kategori
        header("Location: kategori.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "ID kategori tidak ditemukan.";
}
?>
