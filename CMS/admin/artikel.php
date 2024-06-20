<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include_once 'db.php';

// Mengambil data artikel dari database
$sql = "SELECT a.id, a.judul, a.konten, a.created_at, p.nama as penulis, k.nama as kategori, a.penulis_id, a.kategori_id, a.gambar
        FROM artikel a
        JOIN penulis p ON a.penulis_id = p.id
        JOIN kategori k ON a.kategori_id = k.id";
$result = $conn->query($sql);


// Query untuk mengambil daftar kategori
$sql_kategori = "SELECT id, nama FROM kategori";
$result_kategori = $conn->query($sql_kategori);

// Inisialisasi array untuk menyimpan opsi kategori
$kategori_options = array();

// Memasukkan opsi kategori ke dalam array
if ($result_kategori->num_rows > 0) {
    while($row = $result_kategori->fetch_assoc()) {
        $kategori_options[$row['id']] = $row['nama'];
    }
}

// Query untuk mengambil daftar penulis
$sql_penulis = "SELECT id, nama FROM penulis";
$result_penulis = $conn->query($sql_penulis);

// Inisialisasi array untuk menyimpan opsi penulis
$penulis_options = array();

// Memasukkan opsi penulis ke dalam array
if ($result_penulis->num_rows > 0) {
    while($row = $result_penulis->fetch_assoc()) {
        $penulis_options[$row['id']] = $row['nama'];
    }
}

$username = $_SESSION['username']; // Dapatkan nama pengguna dari session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">Menu</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="artikel.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-file-earmark-text"></i></div>
                            Artikel
                        </a>
                        <a class="nav-link" href="kategori.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-bookmark-check-fill"></i></div>
                            Kategori
                        </a>
                        <a class="nav-link" href="penulis.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-person-fill"></i></div>
                            Penulis
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $username; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Artikel</h1>

                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Artikel</li>
                    </ol>
                    <div class="mb-4">
                        <!-- Button trigger modal -->
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="openModal();">
                            Tulis Artikel
                        </a>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Tulis Artikel</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="artikelForm" action="save_artikel.php" method="post" enctype="multipart/form-data">
    <input type="hidden" id="artikel_id" name="artikel_id">
    <div class="mb-3">
        <label for="judul" class="form-label">Judul</label>
        <input type="text" class="form-control" id="judul" name="judul" required>
    </div>
    <div class="mb-3">
        <label for="penulis" class="form-label">Penulis</label>
        <select class="form-select" id="penulis" name="penulis" required>
            <option value="">Cari nama Anda</option>
            <?php
            // Menampilkan opsi penulis
            foreach ($penulis_options as $id => $nama) {
                echo "<option value=\"$id\">$nama</option>";
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <select class="form-select" id="kategori" name="kategori" required>
            <option value="">Pilih Kategori</option>
            <?php
            // Menampilkan opsi kategori
            foreach ($kategori_options as $id => $nama) {
                echo "<option value=\"$id\">$nama</option>";
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="konten" class="form-label">Konten</label>
        <textarea class="form-control" id="konten" name="konten" rows="5" required></textarea>
    </div>
    <div class="mb-3">
        <label for="gambar" class="form-label">Upload Gambar</label>
        <input type="file" class="form-control" id="gambar" name="gambar">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
</form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Daftar Artikel
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
    <tr>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Kategori</th>
        <th>Tanggal</th>
        <th>Konten</th>
        <th>Gambar</th>
        <th>Aksi</th>
    </tr>
</thead>
<tfoot>
    <tr>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Kategori</th>
        <th>Tanggal</th>
        <th>Konten</th>
        <th>Gambar</th>
        <th>Aksi</th>
    </tr>
</tfoot>
<tbody>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Potong konten jika terlalu panjang
            $konten = $row['konten'];
            $max_length = 10; // Panjang maksimum konten yang ditampilkan
            if (strlen($konten) > $max_length) {
                $konten = substr($konten, 0, $max_length) . "...";
            }

            // Periksa apakah gambar ada
            $gambar = $row['gambar'] ? htmlspecialchars($row['gambar']) : "Tidak ada gambar";

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
            echo "<td>" . htmlspecialchars($row['penulis']) . "</td>";
            echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "<td>" . htmlspecialchars($konten) . "</td>";
            echo "<td>" . $gambar . "</td>";
            echo "<td>
                    <button class='btn btn-primary btn-sm' onclick='editArtikel(".json_encode($row).")'>Edit</button>
                    <a href='delete_artikel.php?id=".$row['id']."' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus artikel ini?\")'>Hapus</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No articles found</td></tr>";
    }
    ?>
</tbody>


                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        function openModal() {
            // Reset form
            document.getElementById('artikelForm').reset();
            document.getElementById('artikel_id').value = '';
            document.getElementById('exampleModalLabel').innerText = 'Tulis Artikel';
            document.getElementById('artikelForm').action = 'save_artikel.php';
        }

        function editArtikel(row) {
            // Set form values
            document.getElementById('artikel_id').value = row.id;
            document.getElementById('judul').value = row.judul;
            document.getElementById('penulis').value = row.penulis_id;
            document.getElementById('kategori').value = row.kategori_id;
            document.getElementById('konten').value = row.konten;

            // Set modal title and action
            document.getElementById('exampleModalLabel').innerText = 'Edit Artikel';
            document.getElementById('artikelForm').action = 'save_artikel.php';

            // Show modal
            var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
            modal.show();
        }
    </script>
</body>
</html>
