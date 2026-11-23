<?php
session_start();

if(!isset($_SESSION['customer_login'])){
    header("Location: /spotly/customer_login.php");
    exit;
}

include __DIR__ . '/config/koneksi.php';
include __DIR__ . '/app/models/Cafe.php';

$cafe    = new Cafe($conn);
$favorit = $cafe->getFavoritUser($_SESSION['customer_username']);
?>
<!DOCTYPE html>
<html>
<head>
    <base href="/spotly/">
    <title>Favoritku - Spotly</title>
    <link rel="stylesheet" href="/spotly/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<nav class="navbar">
    <a href="/spotly/" class="logo" style="text-decoration:none;">SPOTLY</a>
    <a href="/spotly/" class="btn-kembali"><i class="bi bi-arrow-left"></i> Beranda</a>
</nav>

<div class="sort-bar">
    <h2>Cafe Favoritku</h2>
</div>

<div class="cafe-container">

    <?php if(mysqli_num_rows($favorit) == 0): ?>
        <p class="ulasan-kosong" style="grid-column: 1 / -1;">
            Belum ada cafe favorit. Klik ikon hati di kartu cafe untuk menyimpannya.
        </p>
    <?php endif; ?>

    <?php while($row = mysqli_fetch_assoc($favorit)): ?>
        <div class="card">

            <div class="card-image">
                <button type="button" class="fav-btn aktif"
                        onclick="hapusFavorit(this, <?= $row['id_cafe']; ?>)"
                        aria-label="Hapus dari favorit"><i class="bi bi-heart"></i><i class="bi bi-heart-fill"></i></button>
                <img src="/spotly/assets/img/<?= htmlspecialchars($row['foto']); ?>">
            </div>

            <div class="card-info">
                <h2><?= htmlspecialchars($row['nama_cafe']); ?></h2>
                <p class="alamat"><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($row['alamat']); ?></p>
                <p class="deskripsi"><?= htmlspecialchars($row['deskripsi']); ?></p>
            </div>

            <div class="card-side">
                <div class="rating">
                    <i class="bi bi-star-fill"></i> <?= $row['rating']; ?> <small>Google</small>
                </div>
                <div class="harga">
                    Mulai dari <br>
                    Rp <?= number_format($row['harga_min'], 0, ',', '.'); ?>
                </div>
                <a href="/spotly/detail/<?= $cafe->buatSlug($row['nama_cafe']); ?>">
                    <button class="detail-btn">Lihat Detail</button>
                </a>
            </div>

        </div>
    <?php endwhile; ?>

</div>

<script>
function hapusFavorit(btn, idCafe){
    fetch('app/controllers/FavoritController', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id_cafe=' + idCafe
    })
    .then(res => res.json())
    .then(data => {
        if(!data.favorit){
            const kartu = btn.closest('.card');
            kartu.style.transition = 'opacity 0.3s';
            kartu.style.opacity = '0';
            setTimeout(() => kartu.remove(), 300);
        }
    });
}
</script>

</body>
</html>