function filterProduk() {
    const kategori = document.getElementById("filterKategori").value.toLowerCase();
    const maxHarga = parseInt(document.getElementById("filterHarga").value) || Infinity;
    const minRating = parseInt(document.getElementById("filterRating").value) || 0;

    document.querySelectorAll('.product-card').forEach(item => {
        const itemKategori = item.dataset.kategori.toLowerCase();
        const itemHarga = parseInt(item.dataset.harga);
        const itemRating = parseInt(item.dataset.rating);

        const cocokKategori = !kategori || itemKategori === kategori;
        const cocokHarga = itemHarga <= maxHarga;
        const cocokRating = itemRating >= minRating;

        if (cocokKategori && cocokHarga && cocokRating) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}
