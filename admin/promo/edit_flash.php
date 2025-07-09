<?php
require '../../koneksi.php';

$id = intval($_GET['id']);
$data = $conn->query("SELECT * FROM flash_sale WHERE id = $id")->fetch_assoc();

if (!$data) {
    echo "Flash sale tidak ditemukan.";
    exit;
}

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

    $conn->query("UPDATE flash_sale SET 
    produk_id = $produk_id,
    nama_produk = '$nama_produk',
    harga_awal = $harga_awal,
    harga_diskon = $harga_diskon,
    waktu_berakhir = '$waktu_berakhir'
    WHERE id = $id");

    header("Location: promo.php");
    exit;
}

// Ambil semua produk dan harga
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
    <title>Edit Flash Sale</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow rounded">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Edit Flash Sale</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="produk_id" class="form-label">Pilih Produk</label>
                        <select name="produk_id" id="produk_id" class="form-select" required onchange="isiHargaAwal()">
                            <?php
                            $produk2 = $conn->query("SELECT id, nama_produk FROM produk");
                            while ($p = $produk2->fetch_assoc()) {
                                $selected = $p['id'] == $data['produk_id'] ? 'selected' : '';
                                echo "<option value='{$p['id']}' $selected>{$p['nama_produk']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga_awal" class="form-label">Harga Awal</label>
                            <input type="number" name="harga_awal" id="harga_awal" class="form-control"
                                value="<?= $data['harga_awal'] ?>" readonly required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga_diskon" class="form-label">Harga Flash Sale</label>
                            <input type="number" name="harga_diskon" id="harga_diskon" class="form-control"
                                value="<?= $data['harga_diskon'] ?>" required>
                        </div>
                    </div>

                    <?php
                    // Hitung sisa durasi dari waktu_berakhir
                    $now = strtotime(date("Y-m-d H:i:s"));
                    $end = strtotime($data['waktu_berakhir']);
                    $sisa_menit = max(0, floor(($end - $now) / 60));
                    $durasi_jam = floor($sisa_menit / 60);
                    $durasi_menit = $sisa_menit % 60;
                    ?>

                    <div class="mb-3">
                        <label class="form-label">Durasi Flash Sale</label>
                        <div class="row">
                            <div class="col-md-6">
                                <select name="durasi_jam" class="form-select" required>
                                    <?php for ($j = 0; $j <= 12; $j++): ?>
                                        <option value="<?= $j ?>" <?= $j == $durasi_jam ? 'selected' : '' ?>><?= $j ?> Jam</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="durasi_menit" class="form-select" required>
                                    <?php for ($m = 0; $m <= 55; $m += 5): ?>
                                        <option value="<?= $m ?>" <?= $m == $durasi_menit ? 'selected' : '' ?>><?= $m ?> Menit</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning">Update Flash Sale</button>
                    <a href="promo.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Harga produk dari PHP
        const hargaProduk = <?= json_encode($produk_json) ?>;

        function isiHargaAwal() {
            const produkId = document.getElementById('produk_id').value;
            const hargaInput = document.getElementById('harga_awal');
            hargaInput.value = hargaProduk[produkId] || '';
        }
    </script>

</body>

</html>