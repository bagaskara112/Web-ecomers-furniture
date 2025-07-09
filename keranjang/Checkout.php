<?php
session_start();
$conn = new mysqli("localhost", "root", "", "furniture_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// === Batalkan Checkout ===
if (isset($_POST['batal_checkout'])) {
    unset($_SESSION['checkout_ids']);
    header("Location: keranjang.php");
    exit;
}

// === Proses Checkout (Simpan Pesanan) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {
    $nama = $conn->real_escape_string($_POST['nama']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $no_hp = $conn->real_escape_string($_POST['no_hp']);
    $metode = $conn->real_escape_string($_POST['metode_pembayaran']);
    $sub_pilihan = $conn->real_escape_string($_POST['sub_pilihan'] ?? '');

    $metodeLengkap = $metode;
    if (!empty($sub_pilihan)) {
        $metodeLengkap .= ' - ' . $sub_pilihan;
    }

    // === Upload Bukti Pembayaran ===
    $fileName = null;
    if ($metode !== 'COD') {
        if (empty($sub_pilihan)) {
            echo "Silakan pilih bank atau e-wallet untuk metode non-COD.";
            exit;
        }

        if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] !== UPLOAD_ERR_OK) {
            echo "Bukti pembayaran harus diunggah untuk metode non-COD.";
            exit;
        }

        $uploadDir = __DIR__ . '/../images/bukti_pembayaran/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['bukti_pembayaran']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $uploadPath)) {
            echo "Gagal upload gambar.";
            exit;
        }
    }


    $checkoutIds = $_SESSION['checkout_ids'] ?? [];
    if (empty($checkoutIds)) {
        header("Location: selesai.php");
        exit;
    }

    $idList = implode(",", array_map('intval', $checkoutIds));
    $result = $conn->query("SELECT * FROM keranjang WHERE id IN ($idList)");

    $totalHarga = 0;
    $checkoutItems = [];
    while ($row = $result->fetch_assoc()) {
        $checkoutItems[] = $row;
        $totalHarga += $row['harga'] * $row['jumlah'];
    }

    // === Simpan Pesanan Utama ===
    $conn->query("INSERT INTO pesanan (nama_pelanggan, alamat, no_hp, metode_pembayaran, total, bukti_pembayaran, tanggal) 
    VALUES ('$nama', '$alamat', '$no_hp', '$metodeLengkap', $totalHarga, '$fileName', NOW())");

    $pesanan_id = $conn->insert_id;

    // === Simpan Detail & Hapus Keranjang ===
    foreach ($checkoutItems as $item) {
        $produk_id = $item['produk_id'];
        $nama_produk = $conn->real_escape_string($item['nama_produk']);
        $harga = $item['harga'];
        $jumlah = $item['jumlah'];

        $conn->query("INSERT INTO detail_pesanan (pesanan_id, produk_id, nama_produk, harga, jumlah) 
        VALUES ($pesanan_id, $produk_id, '$nama_produk', $harga, $jumlah)");
        $conn->query("DELETE FROM keranjang WHERE id = " . $item['id']);
    }

    unset($_SESSION['checkout_ids']);
    header("Location: selesai.php?id=$pesanan_id");
    exit;
}

// === Ambil Data untuk Ditampilkan di Form Checkout ===
$checkoutItems = [];
$totalHarga = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout_id'])) {
    $ids = array_map('intval', $_POST['checkout_id']);
    $_SESSION['checkout_ids'] = $ids;
} elseif (!empty($_SESSION['checkout_ids'])) {
    $ids = $_SESSION['checkout_ids'];
} else {
    header("Location: keranjang.php");
    exit;
}

$idList = implode(",", $ids);
$result = $conn->query("SELECT * FROM keranjang WHERE id IN ($idList)");
while ($row = $result->fetch_assoc()) {
    $checkoutItems[] = $row;
    $totalHarga += $row['harga'] * $row['jumlah'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-4">
        <h2 class="mb-4">📝 Checkout</h2>

        <h5>Rincian Produk:</h5>
        <ul class="list-group mb-4">
            <?php foreach ($checkoutItems as $item): ?>
                <li class="list-group-item d-flex justify-content-between">
                    <?= htmlspecialchars($item['nama_produk']) ?> (<?= $item['jumlah'] ?> x Rp <?= number_format($item['harga'], 0, ',', '.') ?>)
                    <strong>Rp <?= number_format($item['jumlah'] * $item['harga'], 0, ',', '.') ?></strong>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Total</strong>
                <strong>Rp <?= number_format($totalHarga, 0, ',', '.') ?></strong>
            </li>
        </ul>
        <form action="checkout.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Pengiriman</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label">Nomor HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
            </div>
            <div class="mb-3">
                <label for="metode" class="form-label">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode" class="form-control" required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="COD">Bayar di Tempat (COD)</option>
                    <option value="E-Wallet">E-Wallet</option>
                </select>
            </div>

            <!-- Sub-pilihan -->
            <div class="mb-3" id="sub-pilihan-container" style="display: none;">
                <label for="sub_pilihan" class="form-label">Pilih Bank / E-Wallet</label>
                <select class="form-select" id="sub_pilihan" name="sub_pilihan"></select>
            </div>

            <!-- Upload bukti -->
            <div class="mb-3" id="input-bukti-container">
                <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*">
            </div>

            <!-- Info Rekening -->
            <div class="mb-3" id="rekening-info" style="display: none;">
                <div class="alert alert-info">
                    <h6>💳 Info Rekening Pembayaran</h6>
                    <div id="rekening-detail">Silakan pilih metode pembayaran terlebih dahulu.</div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">✅ Selesaikan Pesanan</button>
            </div>
        </form>


        <!-- Tombol Batalkan -->
        <form action="Checkout.php" method="POST" class="mt-2">
            <button type="submit" name="batal_checkout" class="btn btn-danger">❌ Batalkan & Kembali ke Keranjang</button>
        </form>
    </div>
    <script>
        const metodeSelect = document.getElementById('metode');
        const subPilihanContainer = document.getElementById('sub-pilihan-container');
        const subPilihanSelect = document.getElementById('sub_pilihan');
        const buktiInput = document.getElementById('bukti_pembayaran');
        const buktiContainer = document.getElementById('input-bukti-container');
        const rekeningInfo = document.getElementById('rekening-info');
        const rekeningDetail = document.getElementById('rekening-detail');

        const dataPilihan = {
            "Transfer Bank": ["BCA", "BRI", "BNI", "Mandiri"],
            "E-Wallet": ["OVO", "Dana", "GoPay", "ShopeePay"]
        };

        const rekeningMap = {
            "BCA": "Bank BCA - a.n. PT. Furniture Maju<br>Nomor: 1234567890",
            "BRI": "Bank BRI - a.n. PT. Furniture Maju<br>Nomor: 0987654321",
            "BNI": "Bank BNI - a.n. PT. Furniture Maju<br>Nomor: 1122334455",
            "Mandiri": "Bank Mandiri - a.n. PT. Furniture Maju<br>Nomor: 5566778899",
            "OVO": "OVO - a.n. PT. Furniture Maju<br>No HP: 085678987456",
            "Dana": "Dana - a.n. PT. Furniture Maju<br>No HP: 085678987456",
            "GoPay": "GoPay - a.n. PT. Furniture Maju<br>No HP: 085678987456",
            "ShopeePay": "ShopeePay - a.n. PT. Furniture Maju<br>No HP: 085678987456"
        };

        metodeSelect.addEventListener('change', function() {
            const metode = this.value;

            // Reset tampilan
            subPilihanSelect.innerHTML = '';
            rekeningDetail.innerHTML = '';
            rekeningInfo.style.display = 'none';

            if (dataPilihan[metode]) {
                subPilihanContainer.style.display = 'block';
                buktiContainer.style.display = 'block';
                buktiInput.required = true;
                subPilihanSelect.required = true;

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '-- Pilih --';
                subPilihanSelect.appendChild(defaultOption);

                dataPilihan[metode].forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item;
                    opt.textContent = item;
                    subPilihanSelect.appendChild(opt);
                });

            } else if (metode === 'COD') {
                subPilihanContainer.style.display = 'none';
                buktiContainer.style.display = 'none';
                subPilihanSelect.required = false;
                buktiInput.required = false;
            } else {
                // Jika tidak valid
                subPilihanContainer.style.display = 'none';
                buktiContainer.style.display = 'none';
                subPilihanSelect.required = false;
                buktiInput.required = false;
            }
        });

        subPilihanSelect.addEventListener('change', function() {
            const pilihan = this.value;
            if (rekeningMap[pilihan]) {
                rekeningInfo.style.display = 'block';
                rekeningDetail.innerHTML = rekeningMap[pilihan];
            } else {
                rekeningInfo.style.display = 'none';
                rekeningDetail.innerHTML = '';
            }
        });
    </script>



</body>

</html>