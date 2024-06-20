<?php
include_once 'admin/db.php';

// Periksa apakah ID artikel telah disertakan dalam URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data artikel berdasarkan ID
    $sql = "SELECT a.id, a.judul, a.konten, a.created_at, a.gambar, p.nama as penulis, k.nama as kategori
            FROM artikel a
            JOIN penulis p ON a.penulis_id = p.id
            JOIN kategori k ON a.kategori_id = k.id
            WHERE a.id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Tampilkan data artikel
        $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo htmlspecialchars($row['judul']); ?> - Blog Home</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#!">Start Bootstrap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="beranda.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Blog</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page header with logo and tagline-->
    <header class="py-5 bg-light border-bottom mb-4">
        <div class="container">
            <div class="text-center my-5">
                <h1 class="fw-bolder">Article Detail</h1>
            </div>
        </div>
    </header>

    <!-- Page content-->
    <div class="container">
        <div class="row">
            <!-- Article content-->
            <div class="col-lg-8">
                <!-- Article -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($row['judul']); ?></h2>
                        <p class="small text-muted"><?php echo htmlspecialchars($row['created_at']); ?></p>
                        <?php
                        if (!empty($row['gambar'])) {
                            echo "<img class='card-img-top mb-4' src='admin/uploads/" . $row['gambar'] . "' alt=''>";
                        }
                        ?>
                        <p class="card-text"><?php echo htmlspecialchars($row['konten']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Side widgets -->
            <div class="col-lg-4">
                <!-- Search widget -->
                <div class="card mb-4">
                    <div class="card-header">Search</div>
                    <div class="card-body">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Enter search term..." aria-label="Enter search term..." aria-describedby="button-search" />
                            <button class="btn btn-primary" id="button-search" type="button">Go!</button>
                        </div>
                    </div>
                </div>

                <!-- Categories widget -->
                <div class="card mb-4">
                    <div class="card-header">Categories</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <ul class="list-unstyled mb-0">
                                    <li><a href="#!">Web Design</a></li>
                                    <li><a href="#!">HTML</a></li>
                                    <li><a href="#!">Freebies</a></li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="list-unstyled mb-0">
                                    <li><a href="#!">JavaScript</a></li>
                                    <li><a href="#!">CSS</a></li>
                                    <li><a href="#!">Tutorials</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side widget -->
                <div class="card mb-4">
                    <div class="card-header">Side Widget</div>
                    <div class="card-body">You can put anything you want inside of these side widgets. They are easy to use, and feature the Bootstrap 5 card component!</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer-->
    <footer class="py-5 bg-dark">
        
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>
</html>
<?php
    } else {
        echo '<p>Article not found.</p>';
    }
} else {
    echo '<p>Invalid request.</p>';
}

$conn->close();
?>
