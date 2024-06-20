<?php
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['artikel_id'];
    $judul = $_POST['judul'];
    $penulis_id = $_POST['penulis'];
    $kategori_id = $_POST['kategori'];
    $konten = $_POST['konten'];
    $gambar = '';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambar']['tmp_name'];
        $fileName = $_FILES['gambar']['name'];
        $fileSize = $_FILES['gambar']['size'];
        $fileType = $_FILES['gambar']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Tentukan direktori tujuan penyimpanan gambar
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $fileName;

        // Pindahkan file ke direktori tujuan
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            $gambar = $fileName;
        }
    }

    if ($id) {
        // Update artikel yang sudah ada
        $sql = "UPDATE artikel SET judul=?, penulis_id=?, kategori_id=?, konten=?, gambar=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('siissi', $judul, $penulis_id, $kategori_id, $konten, $gambar, $id);
    } else {
        // Tambah artikel baru
        $sql = "INSERT INTO artikel (judul, penulis_id, kategori_id, konten, gambar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('siiss', $judul, $penulis_id, $kategori_id, $konten, $gambar);
    }

    if ($stmt->execute()) {
        header("Location: artikel.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
