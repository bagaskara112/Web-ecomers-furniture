<?php
require '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produk_id = intval($_POST['produk_id']);
    $harga_awal = intval($_POST['harga_awal']);
    $harga_diskon = intval($_POST['harga_diskon']);
    $durasi_jam = intval($_POST['durasi_jam']);
    $durasi_menit = intval($_POST['durasi_menit']);

    $total_durasi = ($durasi_jam * 60) + $durasi_menit;

    $produk = $conn->query("SELECT nama_produk FROM produk WHERE id = $produk_id")->fetch_assoc();
    $nama_produk = $produk['nama_produk'];

    $waktu_berakhir = date("Y-m-d H:i:s", strtotime("+$total_durasi minutes"));

    $conn->query("INSERT INTO flash_sale (produk_id, nama_produk, harga_awal, harga_diskon, waktu_berakhir, status) 
                VALUES ($produk_id, '$nama_produk', $harga_awal, $harga_diskon, '$waktu_berakhir', 'Aktif')");

    header("Location: promo.php");
    exit;
}

$produk = $conn->query("SELECT id, nama_produk, harga FROM produk");
$produk_json = [];
while ($p = $produk->fetch_assoc()) {
    $produk_json[$p['id']] = $p['harga'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Flash Sale</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow rounded">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">Tambah Flash Sale Baru</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="produk_id" class="form-label">Pilih Produk</label>
                        <select name="produk_id" id="produk_id" class="form-select" required onchange="isiHargaAwal()">
                            <option value="" disabled selected>-- Pilih Produk --</option>
                            <?php
                            $produk2 = $conn->query("SELECT id, nama_produk FROM produk");
                            while ($p = $produk2->fetch_assoc()) {
                                echo "<option value='{$p['id']}'>{$p['nama_produk']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga_awal" class="form-label">Harga Awal</label>
                            <input type="number" name="harga_awal" id="harga_awal" class="form-control" readonly required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga_diskon" class="form-label">Harga Flash Sale</label>
                            <input type="number" name="harga_diskon" id="harga_diskon" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Durasi Flash Sale</label>
                        <div class="row">
                            <div class="col-md-6">
                                <select name="durasi_jam" class="form-select" required>
                                    <option value="0">0 Jam</option>
                                    <?php for ($j = 1; $j <= 12; $j++): ?>
                                        <option value="<?= $j ?>"><?= $j ?> Jam</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="durasi_menit" class="form-select" required>
                                    <option value="0">0 Menit</option>
                                    <?php for ($m = 5; $m <= 55; $m += 5): ?>
                                        <option value="<?= $m ?>"><?= $m ?> Menit</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger">Simpan Flash Sale</button>
                    <a href="promo.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Ambil data harga dari PHP
        const hargaProduk = <?= json_encode($produk_json) ?>;

        function isiHargaAwal() {
            const produkId = document.getElementById('produk_id').value;
            document.getElementById('harga_awal').value = hargaProduk[produkId] || '';
        }
    </script>

</body>

</html>