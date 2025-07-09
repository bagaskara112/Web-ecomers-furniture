<?php
require '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produk_id = intval($_POST['produk_id']);
    $diskon = $conn->real_escape_string($_POST['diskon']);

    // Ambil nama produk otomatis
    $produk = $conn->query("SELECT nama_produk FROM produk WHERE id = $produk_id")->fetch_assoc();
    $nama_promo = "Diskon untuk " . $produk['nama_produk'];

    $conn->query("INSERT INTO promo (nama_promo, produk_id, diskon, status) VALUES ('$nama_promo', $produk_id, '$diskon', 'Aktif')");
    header("Location: promo.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Promo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow-sm rounded">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Tambah Promo Baru</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="produk_id" class="form-label">Pilih Produk</label>
                        <select name="produk_id" id="produk_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Produk --</option>
                            <?php
                            $produk = $conn->query("SELECT id, nama_produk FROM produk");
                            while ($p = $produk->fetch_assoc()) {
                                echo "<option value='{$p['id']}'>{$p['nama_produk']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="diskon" class="form-label">Diskon</label>
                        <input type="text" name="diskon" id="diskon" class="form-control" placeholder="Contoh: 20% atau Rp 50.000" required>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan Promo</button>
                    <a href="promo.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

</body>

</html>