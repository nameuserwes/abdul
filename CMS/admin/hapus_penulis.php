<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include_once 'db.php';

$username = $_SESSION['username']; // Dapatkan nama pengguna dari session

// Periksa apakah parameter ID penulis dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus penulis dari database
    $sql = "DELETE FROM penulis WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect kembali ke halaman penulis setelah berhasil menghapus penulis
        header("Location: penulis.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "ID penulis tidak ditemukan.";
}
?>
