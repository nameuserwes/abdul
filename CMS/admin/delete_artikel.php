<?php
include_once 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil nama file gambar yang terkait dengan artikel
    $sql = "SELECT gambar FROM artikel WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $gambar = $row['gambar'];

        // Hapus file gambar dari folder jika ada
        if ($gambar && file_exists('uploads/' . $gambar)) {
            unlink('uploads/' . $gambar);
        }

        // Hapus artikel dari database
        $sql = "DELETE FROM artikel WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            header("Location: artikel.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "No article found with the provided ID.";
    }
} else {
    echo "ID not provided.";
}
?>
