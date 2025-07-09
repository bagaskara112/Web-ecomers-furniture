-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Jun 2025 pada 15.11
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `furniture_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `pesanan_id`, `produk_id`, `nama_produk`, `harga`, `jumlah`) VALUES
(16, 33, 33, 'Kursi Cafe', 400000, 1),
(17, 34, 42, 'Rak Aesthetics', 415000, 1),
(18, 35, 30, 'Dudukan Gitar', 300000, 1),
(19, 35, 33, 'Kursi Cafe', 400000, 1),
(20, 36, 31, 'Kursi Minimalis', 900000, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `flash_sale`
--

CREATE TABLE `flash_sale` (
  `id` int(11) NOT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `harga_awal` int(11) DEFAULT NULL,
  `harga_diskon` int(11) DEFAULT NULL,
  `waktu_berakhir` datetime DEFAULT NULL,
  `status` enum('Aktif','Nonaktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `flash_sale`
--

INSERT INTO `flash_sale` (`id`, `produk_id`, `nama_produk`, `harga_awal`, `harga_diskon`, `waktu_berakhir`, `status`) VALUES
(1, 4, 'Lampu Gantung', 500000, 400000, '2025-06-28 22:19:46', 'Nonaktif'),
(2, 5, 'Kursi Kerja', 500000, 400000, '2025-06-28 21:26:09', 'Nonaktif'),
(3, 5, 'Kursi Kerja', 500000, 300000, '2025-06-29 04:47:05', 'Nonaktif'),
(4, 31, 'Kursi Minimalis', 500000, 400000, '2025-06-28 22:54:52', 'Nonaktif'),
(5, 45, 'Vas Bunga Elegan', 200000, 190000, '2025-06-29 00:07:19', 'Nonaktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontak`
--

CREATE TABLE `kontak` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `tanggal_kirim` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kontak`
--

INSERT INTO `kontak` (`id`, `nama`, `email`, `pesan`, `tanggal_kirim`) VALUES
(2, 'bagas', 'bagas@gmail.com', 'saya ingin mempunyai kursi untuk anak umur 5 th', '2025-06-03 18:57:43'),
(3, 'bagas', 'bagas@gmail.com', 'saya ingin es krim , apakah ada di toko?', '2025-06-03 18:58:24'),
(5, 'bagas', 'bagas@gmail.com', 'saya ingin ada minuman dingin ', '2025-06-28 15:38:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `bukti_pembayaran` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id`, `total`, `nama_pelanggan`, `alamat`, `no_hp`, `metode_pembayaran`, `tanggal`, `bukti_pembayaran`) VALUES
(33, 400000, 'Bagas', 'Blitar Kota', '085678987456', 'COD', '2025-06-29 18:48:18', ''),
(34, 415000, 'Budi', 'Jatinom', '0845374232', 'E-Wallet - OVO', '2025-06-29 18:58:54', '1751198334_1.jpg'),
(35, 700000, 'adi', 'jln imam bonjol kota kediri', '09876579852', 'COD', '2025-06-29 19:00:22', ''),
(36, 900000, 'santika ', 'jln bawean Kota Malang', '085678456123', 'COD', '2025-06-29 19:02:05', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `alt` text DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `harga`, `gambar`, `alt`, `kategori`, `deskripsi`) VALUES
(3, 'Meja Kursi Santai Set', 1500000, 'images/meja_kursi_santai_set.jpg', 'Meja', 'Set', ''),
(4, 'Lampu Gantung', 500000, 'images/lampu_gantung.jpg', 'Lampu', 'Lampu', NULL),
(5, 'Kursi Kerja', 350000, 'images/kursi_kerja.jpg', 'Kursi', 'Kursi', ''),
(30, 'Dudukan Gitar', 300000, 'images/dudukan_gitar.jpg', 'Dudukan Gitar', 'Aksesoris', 'Ini adalah Dudukan berbahan Baja tahan api'),
(31, 'Kursi Minimalis', 900000, 'images/kursi1.jpg', 'Kursi', 'Kursi', ''),
(33, 'Kursi Cafe', 450000, 'images/kursi_cafe.jpg', 'Kursi Cafe', 'Kursi', ''),
(36, 'Meja Kayu Modern', 1000000, 'images/meja1.jpg', 'Meja 1', 'Meja', NULL),
(39, 'Meja Kursi Set', 4500000, 'images/meja_kursi_set.jpg', 'Meja Kursi Set', 'Set', ''),
(40, 'Meja Kursi Anak Set', 700000, 'images/mk_anak_set.jpg', 'MK Anak Set', 'Set', NULL),
(41, 'Nampan Kayu', 90000, 'images/nampan_kayu.jpg', 'Nampan Kayu', 'Aksesoris', NULL),
(42, 'Rak Aesthetics', 500000, 'images/rak.jpg', 'Rak', 'Rak', NULL),
(45, 'Vas Bunga Elegan', 200000, 'images/vas_b2.jpg', 'Vas B2', 'Vas', NULL),
(46, 'Vas Bunga Rustic', 200000, 'images/vas_b3.jpg', 'Vas B3', 'Vas', NULL),
(53, 'Sofa Set ', 2000000, 'images/1751139047_sofa.jpg', NULL, NULL, 'ini adalah sofa empuk berkualitas tinggi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `promo`
--

CREATE TABLE `promo` (
  `id` int(11) NOT NULL,
  `nama_promo` varchar(255) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `diskon` varchar(100) DEFAULT NULL,
  `status` enum('Aktif','Nonaktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `promo`
--

INSERT INTO `promo` (`id`, `nama_promo`, `produk_id`, `diskon`, `status`) VALUES
(1, 'Diskon untuk Meja Kursi Santai Set', 3, '20%', 'Nonaktif'),
(2, 'Diskon untuk Meja Kayu Modern', 36, '20', 'Nonaktif'),
(3, 'Diskon untuk Dudukan Gitar', 30, '5', 'Nonaktif'),
(4, 'Diskon untuk Meja Kursi Anak Set', 40, '10', 'Nonaktif'),
(5, 'Diskon untuk Nampan Kayu', 41, '10', 'Nonaktif'),
(6, 'Diskon untuk Nampan Kayu', 41, '10%', 'Nonaktif'),
(7, 'Diskon untuk Kursi Kerja', 5, '50000', 'Nonaktif'),
(8, 'Diskon untuk Rak Aesthetics', 42, '85000', 'Aktif'),
(9, 'Diskon untuk Kursi Cafe', 33, '50000', 'Aktif'),
(10, 'Diskon untuk Meja Kayu Modern', 36, '50000', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ulasan`
--

CREATE TABLE `ulasan` (
  `id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `komentar` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `produk_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ulasan`
--

INSERT INTO `ulasan` (`id`, `rating`, `komentar`, `gambar`, `tanggal`, `produk_id`) VALUES
(15, 5, 'josjiz bos', 'uploads/luca-ercolani-EihDVYXyY3Y-unsplash.jpg', '2025-06-04 00:47:13', 30),
(18, 5, 'oke juga', '', '2025-06-04 01:40:09', 30),
(19, 5, 'mantap polllll', '', '2025-06-04 01:40:20', 30),
(20, 5, 'design yang jos', '', '2025-06-04 02:07:26', 45),
(21, 5, 'sangat elegan', '', '2025-06-04 02:07:36', 45),
(22, 5, 'cocok untuk ruangan bersantai', '', '2025-06-04 02:07:59', 45),
(23, 5, 'cocok untuk nuansa putih putih', '', '2025-06-04 02:09:13', 42),
(24, 5, 'bisa untuk menaruh banyak barang', '', '2025-06-04 02:09:31', 42),
(25, 5, 'uk nya bisa di costum', '', '2025-06-04 02:09:48', 42),
(26, 3, 'lumayan bagus kualitasnya', 'uploads/meja_kursi_santai_set.jpg', '2025-06-04 18:17:07', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `alamat` text NOT NULL,
  `nomor_hp` varchar(15) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `alamat`, `nomor_hp`, `jenis_kelamin`) VALUES
(1, 'bagaskara', 'blitar', '087654235468', 'Laki-Laki');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`);

--
-- Indeks untuk tabel `flash_sale`
--
ALTER TABLE `flash_sale`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produk_id` (`produk_id`);

--
-- Indeks untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produk` (`produk_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `flash_sale`
--
ALTER TABLE `flash_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT untuk tabel `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `promo`
--
ALTER TABLE `promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`);

--
-- Ketidakleluasaan untuk tabel `flash_sale`
--
ALTER TABLE `flash_sale`
  ADD CONSTRAINT `flash_sale_ibfk_1` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`);

--
-- Ketidakleluasaan untuk tabel `promo`
--
ALTER TABLE `promo`
  ADD CONSTRAINT `fk_produk_id` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`);

--
-- Ketidakleluasaan untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `fk_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
