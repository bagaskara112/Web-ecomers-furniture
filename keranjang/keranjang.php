<?php
$conn = new mysqli("localhost", "root", "", "furniture_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM keranjang WHERE id = $id");
    header("Location: keranjang.php");
    exit;
}

if (isset($_GET['kosongkan'])) {
    $conn->query("DELETE FROM keranjang");
    header("Location: keranjang.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_jumlah'])) {
    $id = intval($_POST['id']);
    $aksi = $_POST['update_jumlah'];

    if ($aksi === 'kurangi') {
        $conn->query("UPDATE keranjang SET jumlah = jumlah - 1 WHERE id = $id");
        $conn->query("DELETE FROM keranjang WHERE id = $id AND jumlah <= 0");
    } elseif ($aksi === 'tambah') {
        $conn->query("UPDATE keranjang SET jumlah = jumlah + 1 WHERE id = $id");
    }

    header("Location: keranjang.php");
    exit;
}

// Tambahkan blok untuk tambah ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $produk_id = intval($_POST['produk_id']);
    $nama = $conn->real_escape_string($_POST['nama']);
    $harga = intval($_POST['harga']);
    $jumlah = intval($_POST['jumlah']);
    $gambar = $conn->real_escape_string($_POST['gambar']);

    $cek = $conn->query("SELECT * FROM keranjang WHERE produk_id = $produk_id");
    if ($cek->num_rows > 0) {
        $conn->query("UPDATE keranjang SET jumlah = jumlah + $jumlah WHERE produk_id = $produk_id");
    } else {
        $conn->query("INSERT INTO keranjang (produk_id, nama_produk, harga, jumlah, gambar) VALUES ($produk_id, '$nama', $harga, $jumlah, '$gambar')");
    }

    header("Location: keranjang.php");
    exit;
}

$result = $conn->query("SELECT * FROM keranjang");
$total = 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .input-group form {
            display: inline;
        }
    </style>
    <script>
        function confirmDelete(id) {
            if (confirm("Yakin ingin menghapus item ini?")) {
                window.location.href = 'keranjang.php?hapus=' + id;
            }
        }

        function confirmKosongkan() {
            if (confirm("Yakin ingin mengosongkan seluruh keranjang?")) {
                window.location.href = 'keranjang.php?kosongkan=true';
            }
        }
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Furniture E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../beranda.html">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="../produk.php">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="../topproduk.php">Top Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="../ulasan.php">Ulasan</a></li>
                    <li class="nav-item"><a class="nav-link" href="../tentangkami.html">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="../kontakkami.php">Kontak</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Keranjang</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/project_joomla">Tips Perawatan (new)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <h2 class="mb-4">🛒 Keranjang Belanja</h2>
        <a href="../beranda.html" class="btn btn-secondary w-100 mb-3">
            ← Kembali ke Beranda
        </a>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()):
                $subtotal = $row['harga'] * $row['jumlah'];
                $total += $subtotal;
            ?>
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex flex-column">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="checkout_id[]" value="<?= $row['id'] ?>" form="checkoutForm">
                                <label class="form-check-label" for="produk<?= $row['id'] ?>">Pilih untuk Checkout</label>
                            </div>
                            <h5><?= htmlspecialchars($row['nama_produk']) ?></h5>
                            <p>Harga: Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                            <p>Total: Rp <?= number_format($subtotal, 0, ',', '.') ?></p>
                            <div class="input-group">
                                <form method="POST" action="keranjang.php" class="d-flex">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="update_jumlah" value="kurangi" class="btn btn-outline-secondary">−</button>
                                    <input type="text" class="form-control text-center mx-1" value="<?= $row['jumlah'] ?>" readonly style="width: 60px;">
                                    <button type="submit" name="update_jumlah" value="tambah" class="btn btn-outline-secondary">+</button>
                                </form>
                            </div>
                            <button type="button" onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-sm btn-danger mt-2 w-50">🗑 Hapus</button>
                        </div>
                        <img src="../<?= htmlspecialchars($row['gambar']) ?>" alt="gambar produk" class="img-thumbnail" width="120">
                    </div>
                </div>
            <?php endwhile; ?>

            <form method="POST" action="Checkout.php" id="checkoutForm">
                <div class="text-end">
                    <h4>Total Belanja: Rp <?= number_format($total, 0, ',', '.') ?></h4>
                    <button type="submit" class="btn btn-primary">Checkout Produk Terpilih</button>
                    <button type="button" onclick="confirmKosongkan()" class="btn btn-outline-danger ms-2">Kosongkan Keranjang</button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-info">Keranjang Anda kosong.</div>
        <?php endif; ?>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <div class="container">
            <p class="mb-0">&copy; 2025 Furniture E-Commerce</p>
        </div>
    </footer>
</body>

</html>