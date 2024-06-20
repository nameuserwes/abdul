<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include_once 'db.php';

// Pastikan parameter id artikel dikirimkan melalui URL
if (!isset($_GET['id'])) {
    header("Location: artikel.php");
    exit();
}

$id = $_GET['id'];

// Ambil data artikel berdasarkan id
$sql = "SELECT * FROM artikel WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $judul = $row['judul'];
    $penulis_id = $row['penulis_id'];
    $kategori_id = $row['kategori_id'];
    $konten = $row['konten'];
} else {
    // Artikel tidak ditemukan
    header("Location: artikel.php");
    exit();
}

// Jika formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $penulis_id = $_POST['penulis'];
    $kategori_id = $_POST['kategori'];
    $konten = $_POST['konten'];

    // Perbarui data artikel di database
    $sql_update = "UPDATE artikel SET judul='$judul', penulis_id=$penulis_id, kategori_id=$kategori_id, konten='$konten' WHERE id=$id";

    if ($conn->query($sql_update) === TRUE) {
        // Redirect ke halaman artikel setelah mengubah artikel
        header("Location: artikel.php");
        exit();
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
}
?>

<!-- Formulir untuk mengubah artikel -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="post">
    <div class="mb-3">
        <label for="judul" class="form-label">Judul</label>
        <input type="text" class="form-control" id="judul" name="judul" value="<?php echo $judul; ?>" required>
    </div>
    <div class="mb-3">
        <label for="penulis" class="form-label">Penulis</label>
        <select class="form-select" id="penulis" name="penulis" required>
            <!-- Opsi penulis -->
        </select>
    </div>
    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <select class="form-select" id="kategori" name="kategori" required>
            <!-- Opsi kategori -->
        </select>
    </div>
    <div class="mb-3">
        <label for="konten" class="form-label">Konten</label>
        <textarea class="form-control" id="konten" name="konten" rows="5" required><?php echo $konten; ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="artikel.php" class="btn btn-secondary">Batal</a>
</form>
