<?php
require '../koneksi.php';

// Ambil ID produk dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query data produk + promo + flash_sale
$stmt = $conn->prepare("SELECT 
    produk.*, 
    promo.nama_promo, promo.diskon,
    flash_sale.harga_diskon, flash_sale.waktu_berakhir,
    IFNULL(AVG(ulasan.rating), 0) AS rata_rating
FROM produk 
LEFT JOIN promo ON produk.id = promo.produk_id AND promo.status = 'Aktif'
LEFT JOIN flash_sale ON produk.id = flash_sale.produk_id AND flash_sale.status = 'Aktif'
LEFT JOIN ulasan ON produk.id = ulasan.produk_id
WHERE produk.id = ?
GROUP BY produk.id");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();

if (!$produk) {
    echo "<h2 class='text-center mt-5'>Produk tidak ditemukan.</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($produk['nama_produk']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f9f6f1;
        }

        .badge-flash {
            background-color: #e60023;
        }

        .badge-promo {
            background-color: #007bff;
        }

        .harga-asli {
            text-decoration: line-through;
            color: #888;
        }

        .harga-diskon {
            color: #d43f3a;
            font-size: 1.6rem;
            font-weight: bold;
        }

        .harga-normal {
            font-size: 1.6rem;
            font-weight: bold;
            color: #28a745;
        }

        .rating {
            color: #ffc107;
            font-size: 1.2rem;
        }

        .produk-deskripsi {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>

<body class="container py-5">
    <a href="../produk.php" class="btn btn-secondary mb-4">← Kembali ke Produk</a>

    <div class="row">
        <div class="col-md-5 text-center mb-4">
            <img src="../<?= htmlspecialchars($produk['gambar']) ?>" class="img-fluid shadow" style="max-height: 400px;" alt="<?= htmlspecialchars($produk['alt']) ?>">
        </div>
        <div class="col-md-7 produk-deskripsi">
            <h2><?= htmlspecialchars($produk['nama_produk']) ?></h2>

            <!-- Rating -->
            <div class="rating mb-2">
                <?php
                $rating = round($produk['rata_rating']);
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $rating ? '★' : '☆';
                }
                ?>
                <span class="text-muted">(<?= number_format($produk['rata_rating'], 1) ?> dari 5)</span>
            </div>

            <!-- Badge Promo/Flash Sale -->
            <?php if (!empty($produk['harga_diskon'])): ?>
                <span class="badge badge-flash text-white mb-2">Flash Sale</span>
            <?php elseif (!empty($produk['nama_promo'])): ?>
                <span class="badge badge-promo text-white mb-2">Promo: <?= htmlspecialchars($produk['nama_promo']) ?></span>
            <?php endif; ?>

            <!-- Harga -->
            <p class="mt-2">
                <?php if (!empty($produk['harga_diskon'])): ?>
                    <span class="harga-asli">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></span><br>
                    <span class="harga-diskon">Rp <?= number_format($produk['harga_diskon'], 0, ',', '.') ?></span>
                <?php elseif (!empty($produk['diskon']) && is_numeric($produk['diskon'])): ?>
                    <span class="harga-asli">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></span><br>
                    <span class="harga-diskon">Rp <?= number_format($produk['harga'] - $produk['diskon'], 0, ',', '.') ?></span>
                <?php else: ?>
                    <span class="harga-normal">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></span>
                <?php endif; ?>
            </p>

            <!-- Info Tambahan -->
            <p><strong>Kategori:</strong> <?= htmlspecialchars($produk['kategori']) ?></p>
            <p><?= nl2br(htmlspecialchars($produk['deskripsi'])) ?></p>

            <!-- Form Tambah ke Keranjang -->
            <form method="post" action="../keranjang/keranjang.php">
                <input type="hidden" name="produk_id" value="<?= $produk['id'] ?>">
                <input type="hidden" name="nama" value="<?= htmlspecialchars($produk['nama_produk']) ?>">
                <input type="hidden" name="gambar" value="<?= htmlspecialchars($produk['gambar']) ?>">
                <input type="hidden" name="harga" value="<?=
                                                            !empty($produk['harga_diskon']) ? $produk['harga_diskon'] : (!empty($produk['diskon']) && is_numeric($produk['diskon']) ? ($produk['harga'] - $produk['diskon']) : $produk['harga'])
                                                            ?>">

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah:</label>
                    <input type="number" name="jumlah" class="form-control" value="1" min="1" required style="max-width: 120px;">
                </div>

                <button type="submit" name="add_to_cart" class="btn btn-primary">
                    + Tambah ke Keranjang
                </button>
            </form>

            <!-- Countdown Script -->
            <?php if (!empty($produk['waktu_berakhir'])): ?>
                <p class="mt-3 text-danger fw-bold" id="countdown">Loading countdown...</p>
                <script>
                    const countdownEl = document.getElementById("countdown");
                    const saleEnd = <?= strtotime($produk['waktu_berakhir']) * 1000 ?>;

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const distance = saleEnd - now;

                        if (distance <= 0) {
                            countdownEl.innerText = "Flash Sale telah berakhir!";
                            return;
                        }

                        const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
                        const minutes = Math.floor((distance / (1000 * 60)) % 60);
                        const seconds = Math.floor((distance / 1000) % 60);

                        countdownEl.innerText = `Flash Sale berakhir dalam: ${hours}j ${minutes}m ${seconds}d`;
                    }

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                </script>
            <?php endif; ?>
        </div>
    </div>


</body>

</html>