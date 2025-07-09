<?php
require '../../koneksi.php';

$id = intval($_GET['id']);
$data = $conn->query("SELECT * FROM promo WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produk_id = intval($_POST['produk_id']);
    $diskon = $conn->real_escape_string($_POST['diskon']);

    $produk = $conn->query("SELECT nama_produk FROM produk WHERE id = $produk_id")->fetch_assoc();
    $nama_promo = "Diskon untuk " . $produk['nama_produk'];

    $conn->query("UPDATE promo SET nama_promo='$nama_promo', produk_id=$produk_id, diskon='$diskon' WHERE id=$id");
    header("Location: promo.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Promo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow-sm rounded">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Edit Promo</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="produk_id" class="form-label">Pilih Produk</label>
                        <select name="produk_id" id="produk_id" class="form-select" required>
                            <?php
                            $produk = $conn->query("SELECT id, nama_produk FROM produk");
                            while ($p = $produk->fetch_assoc()) {
                                $selected = ($p['id'] == $data['produk_id']) ? 'selected' : '';
                                echo "<option value='{$p['id']}' $selected>{$p['nama_produk']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="diskon" class="form-label">Diskon</label>
                        <input type="text" name="diskon" id="diskon" class="form-control" value="<?= htmlspecialchars($data['diskon']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Promo</button>
                    <a href="promo.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

</body>

</html>