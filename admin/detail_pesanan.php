<?php
$conn = new mysqli("localhost", "root", "", "furniture_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// === Proses Hapus Pesanan ===
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $hapusId = intval($_GET['hapus']);

    // Ambil bukti pembayaran (jika ada) sebelum hapus
    $getBukti = $conn->query("SELECT bukti_pembayaran FROM pesanan WHERE id = $hapusId");
    $buktiFile = '';
    if ($getBukti && $getBukti->num_rows > 0) {
        $row = $getBukti->fetch_assoc();
        $buktiFile = $row['bukti_pembayaran'];
    }

    // Hapus data dari detail_pesanan
    $conn->query("DELETE FROM detail_pesanan WHERE pesanan_id = $hapusId");

    // Hapus data dari pesanan
    $conn->query("DELETE FROM pesanan WHERE id = $hapusId");

    // Hapus file bukti pembayaran jika ada
    if (!empty($buktiFile)) {
        $filePath = "../images/bukti_pembayaran/" . $buktiFile;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Redirect ulang
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Ambil semua data pesanan gabung dengan detail
$result = $conn->query("
    SELECT 
        p.id AS pesanan_id,
        p.nama_pelanggan,
        p.alamat,
        p.no_hp,
        p.metode_pembayaran,
        p.total,
        p.bukti_pembayaran,
        p.tanggal,
        d.nama_produk,
        d.harga,
        d.jumlah
    FROM pesanan p
    LEFT JOIN detail_pesanan d ON p.id = d.pesanan_id
    ORDER BY p.tanggal DESC
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Furniture</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<!-- Bagian HTML mulai dari <body> -->

<body class="d-flex flex-column min-vh-100 bg-light">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Furniture Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="wishlist.php">Wishlist</a></li>
                    <li class="nav-item"><a class="nav-link" href="promo/promo.php">Promo</a></li>
                    <li class="nav-item"><a class="nav-link" href="ulasan_admin.php">Ulasan</a></li>
                    <li class="nav-item"><a class="nav-link active" href="detail_pesanan.php">Pesanan</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <main class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">📋 Detail Pesanan Pelanggan</h4>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Alamat</th>
                                    <th>No HP</th>
                                    <th>Metode</th>
                                    <th>Bukti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $last_id = null;
                                while ($row = $result->fetch_assoc()):
                                    $pesanan_id = $row['pesanan_id'];
                                    $is_new = $pesanan_id !== $last_id;
                                    $last_id = $pesanan_id;
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($row['jumlah']) ?></td>
                                        <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($row['alamat']) ?></td>
                                        <td><?= htmlspecialchars($row['no_hp']) ?></td>
                                        <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                                        <td>
                                            <?php
                                            $bukti = htmlspecialchars($row['bukti_pembayaran']);
                                            $path = "../images/bukti_pembayaran/" . $bukti;
                                            if (!empty($bukti) && file_exists($path)): ?>
                                                <a href="<?= $path ?>" target="_blank">
                                                    <img src="<?= $path ?>" alt="Bukti" width="50" class="img-thumbnail">
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($is_new): ?>
                                                <a href="?hapus=<?= $pesanan_id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center">Belum ada data pesanan.</div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        &copy; <?= date('Y') ?> Furniture E-Commerce Admin
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>