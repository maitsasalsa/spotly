<?php
session_start();

if(!isset($_SESSION['customer_login'])){
    header("Location: /spotly/customer_login.php");
    exit;
}

include 'app/controllers/DetailController.php';

function tampilBintang($nilai){
    $persen = max(0, min(100, ($nilai / 5) * 100));
    return '<span class="bintang-baca">'
         . '<span class="bintang-isi" style="width:' . $persen . '%">★★★★★</span>'
         . '★★★★★</span>';
}

?>

<!DOCTYPE html>
<html>
<head>

    <base href="/spotly/">

    <title><?= $data['nama_cafe']; ?></title>

    <link rel="stylesheet" href="/spotly/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            padding: 40px 0;
        }

        .spotly-gallery {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
            width: 85%;
            margin: 0 auto 30px auto;
        }

        .main-photo img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 24px;
        }

        .side-photos {
            display: grid;
            grid-template-rows: 1fr 1fr;
            gap: 15px;
        }

        .side-photos img, .foto-terakhir img {
            width: 100%;
            height: 217px;
            object-fit: cover;
            border-radius: 24px;
        }

        .foto-terakhir {
            position: relative;
            cursor: pointer;
        }

        .overlay-foto {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            color: white;
            font-size: 20px;
            font-weight: 600;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 24px;
            backdrop-filter: blur(2px);
            transition: 0.3s;
        }

        .overlay-foto:hover {
            background: rgba(0,0,0,0.5);
        }

        .lihat-foto {
            background: #6F4E37; /* Mengubah hitam pekat jadi cokelat Spotly */
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 12px; /* Mengikuti lengkungan modern 12px */
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background 0.3s ease;
        }

        .lihat-foto:hover {
            background: #563c2a;
        }

        .popup-foto{
            display:none;
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.85);
            justify-content:center;
            align-items:center;
            z-index:9999;
        }

        .popup-content{
            width:90%;
            max-width:1200px;
            max-height:90vh;
            overflow:auto;

            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
            gap:10px;
        }

        .popup-img{
            width:100%;
            height:220px;
            object-fit:cover;
            border-radius:10px;
        }

        .close-popup{
            position:absolute;
            top:20px;
            right:30px;
            color:white;
            font-size:35px;
            cursor:pointer;
        }

        .foto-besar{
            max-width:70%;
            max-height:70vh;
            border-radius:24px;

            margin-bottom:120px;
        }

        .thumbnail-list{
            position:absolute;
            bottom:20px;
            left:50%;
            transform:translateX(-50%);

            display:flex;
            gap:10px;
            justify-content:center;
            align-items:center;

            width:auto;
        }

        .thumb{
            width:100px;
            height:70px;
            object-fit:cover;
            border-radius:10px;
            cursor:pointer;
            opacity: 0.6;
            transition: 0.3s;
        }

        .thumb:hover, .thumb.active {
            opacity: 1;
        }

        .prev,
        .next{
            position:absolute;
            top:50%;

            transform:translateY(-50%);

            width:50px;
            height:50px;

            border:none;
            border-radius:50%;

            background:white;
            cursor:pointer;

            font-size:24px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .prev{
            left:30px;
        }

        .next{
            right:30px;
        }

        .rekomendasi {
            width: 85%;
            margin: 60px auto 0 auto;
        }

        .judul-rekomendasi {
            color: #4a2c11;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .rekomendasi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .rekomendasi-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(111, 78, 55, 0.04);
            transition: .4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .rekomendasi-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(111, 78, 55, 0.08);
        }

        .rekomendasi-card img{
            width:100%;
            height:200px;
            object-fit:cover;
        }

        .rekomendasi-content{
            padding:20px;
        }

        .rekomendasi-card h3 {
            margin: 0 0 10px;
            color: #4a2c11;
            font-size: 20px;
            font-weight: 700;
        }

        .rekomendasi-rating {
            color: #f5a623;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .rekomendasi-btn {
            display: inline-block;
            background: #6F4E37; /* Mengubah cokelat pudar jadi cokelat premium khas Spotly */
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 12px; /* Mengubah lengkungan 8px jadi 12px biar modern */
            font-size: 13px;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .rekomendasi-btn:hover {
            background: #563c2a; /* Efek gelap sedikit saat kursor menempel */
        }
    </style>
</head>
<body>

<div class="detail-container">

    <?php
    $gambar = [];
    mysqli_data_seek($foto, 0);

    while($f = mysqli_fetch_assoc($foto)){
        $gambar[] = $f['nama_foto'];
    }
    ?>

    <div class="spotly-gallery">
        <div class="main-photo">
            <img src="/spotly/assets/img/<?= $gambar[0]; ?>">
        </div>

        <div class="side-photos">
            <?php if(count($gambar) > 1){ ?>
                <img src="/spotly/assets/img/<?= $gambar[1]; ?>">
            <?php } ?>

            <?php if(count($gambar) > 2){ ?>
                <div class="foto-terakhir" onclick="document.getElementById('popupFoto').style.display='flex'">
                    <img src="/spotly/assets/img/<?= $gambar[2]; ?>">
                    <div class="overlay-foto">
                        Lihat Semua Foto
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div id="popupFoto" class="popup-foto">
        <span class="close-popup" onclick="document.getElementById('popupFoto').style.display='none'"><i class="bi bi-x-lg"></i></span>

        <button class="prev" onclick="gantiFoto(-1)"><i class="bi bi-chevron-left"></i></button>

        <img id="fotoBesar" src="/spotly/assets/img/<?= $gambar[0]; ?>" class="foto-besar">

        <button class="next" onclick="gantiFoto(1)"><i class="bi bi-chevron-right"></i></button>

        <div class="thumbnail-list">
            <?php foreach($gambar as $g){ ?>
                <img src="/spotly/assets/img/<?= $g; ?>" onclick="pilihFoto(this.src)" class="thumb">
            <?php } ?>
        </div>
    </div>

    <div class="detail-container" style="width: 85%; margin: 0 auto; background: white; border-radius: 24px; padding: 40px; box-shadow: 0 10px 30px rgba(111, 78, 55, 0.04); box-sizing: border-box;">
        <div class="header-cafe" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1 class="nama-cafe" style="margin: 0;"><?= $data['nama_cafe']; ?></h1>
            <div class="rating-badge">
    <div><i class="bi bi-star-fill"></i> <?= $data['rating']; ?> <small>di Google Maps</small></div>
    <div>
        <?php if($ringkasan['jumlah'] > 0): ?>
                        <?= tampilBintang($ringkasan['rata']); ?> <?= $ringkasan['rata']; ?>
            <small>dari <?= $ringkasan['jumlah']; ?> ulasan Spotly</small>
        <?php else: ?>
            <small>Belum ada ulasan Spotly</small>
        <?php endif; ?>
    </div>
</div>
        </div>

        <div class="detail-info-group">
            <p><strong><i class="bi bi-geo-alt-fill"></i> Alamat</strong><br><?= $data['alamat']; ?></p>
        </div>

        <div class="detail-info-group">
            <p><strong><i class="bi bi-card-text"></i> Deskripsi</strong><br><?= $data['deskripsi']; ?></p>
        </div>

        <div class="detail-info-group">
            <p><strong><i class="bi bi-clock"></i> Jam Buka</strong><br><?= $data['jam_buka']; ?></p>
        </div>

        <div class="detail-info-group">
            <p><strong><i class="bi bi-wallet2"></i> Kisaran Harga</strong><br>Rp <?= number_format($data['harga_min'],0,',','.'); ?> - Rp <?= number_format($data['harga_max'],0,',','.'); ?></p>
        </div>

        <div class="detail-actions">
            <a href="<?= $data['maps_link']; ?>" target="_blank" class="btn-maps"><i class="bi bi-geo-alt-fill"></i> Buka Google Maps</a>
            <a href="/spotly/" class="btn-kembali">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
        </div>
    </div>

    <div class="ulasan-section">
    <h2 class="judul-rekomendasi">Ulasan Pengunjung Spotly</h2>

    <?php if(isset($_SESSION['flash_ulasan'])): ?>
        <div class="ulasan-notif"><?= $_SESSION['flash_ulasan']; ?></div>
        <?php unset($_SESSION['flash_ulasan']); ?>
    <?php endif; ?>

    <form method="post"
          action="/spotly/detail/<?= $cafe->buatSlug($data['nama_cafe']); ?>"
          class="ulasan-form"
          id="formUlasan"
          style="<?= $ulasanSaya ? 'display:none;' : ''; ?>">

        <label class="ulasan-label">
            <?= $ulasanSaya ? 'Edit ulasanmu' : 'Tulis ulasanmu'; ?>
        </label>

        <div class="bintang-input">
    <?php for($v = 5; $v >= 0.5; $v -= 0.5):
        $idb = 'b' . str_replace('.', '_', (string)$v); ?>
        <input type="radio" id="<?= $idb; ?>" name="bintang"
               value="<?= $v; ?>" required
               <?= ($ulasanSaya && (float)$ulasanSaya['bintang'] == $v) ? 'checked' : ''; ?>>
        <label for="<?= $idb; ?>"
               class="<?= fmod($v, 1) == 0.5 ? 'half' : 'full'; ?>"></label>
    <?php endfor; ?>
</div>

        <textarea name="komentar" maxlength="500" rows="4"
                  placeholder="Ceritakan pengalamanmu di cafe ini..."><?= htmlspecialchars($ulasanSaya['komentar'] ?? ''); ?></textarea>

        <div class="ulasan-aksi">
            <button type="submit" class="btn-ulasan">
                <?= $ulasanSaya ? 'Simpan Perubahan' : 'Kirim Ulasan'; ?>
            </button>
            <?php if($ulasanSaya): ?>
                <button type="button" class="btn-batal" onclick="tutupFormUlasan()">Batal</button>
            <?php endif; ?>
        </div>
    </form>

    <div class="ulasan-list">
        <?php if(mysqli_num_rows($ulasan) == 0): ?>
            <p class="ulasan-kosong">Belum ada ulasan, jadilah yang pertama!</p>
        <?php endif; ?>

        <?php while($u = mysqli_fetch_assoc($ulasan)):
            $milikku = ($u['username'] === $_SESSION['customer_username']); ?>

            <div class="ulasan-item <?= $milikku ? 'ulasan-milikku' : ''; ?>">
                <div class="ulasan-head">
                    <strong>
                        <?= htmlspecialchars($u['username']); ?>
                        <?php if($milikku): ?>
                            <span class="badge-saya">Ulasanmu</span>
                        <?php endif; ?>
                    </strong>
                    <span class="ulasan-bintang">
    <?= tampilBintang($u['bintang']); ?> <small><?= $u['bintang']; ?></small>
</span>
                </div>

                <p><?= nl2br(htmlspecialchars($u['komentar'])); ?></p>
                <small><?= date('d M Y', strtotime($u['tanggal'])); ?></small>

                <?php if($milikku): ?>
                    <div class="ulasan-aksi">
                        <button type="button" class="btn-edit" onclick="bukaFormUlasan()">Edit</button>

                        <form method="post"
                              action="/spotly/detail/<?= $cafe->buatSlug($data['nama_cafe']); ?>"
                              onsubmit="return confirm('Hapus ulasanmu untuk cafe ini?');"
                              style="display:inline;">
                            <input type="hidden" name="aksi" value="hapus">
                            <button type="submit" class="btn-hapus">Hapus</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

    <div class="rekomendasi">
        <hr style="border: 0; height: 1px; background: #eee; margin: 50px 0 30px 0">
        <h2 class="judul-rekomendasi">Cafe Rekomendasi Lainnya</h2>

        <div class="rekomendasi-grid">
            <?php while($r = mysqli_fetch_assoc($rekomendasi)){ ?>
                <div class="rekomendasi-card">
                    <img src="/spotly/assets/img/<?= $r['foto']; ?>">
                    <div class="rekomendasi-content">
                        <h3><?= $r['nama_cafe']; ?></h3>
                        <div class="rekomendasi-rating">
                            <i class="bi bi-star-fill"></i> <?= $r['rating']; ?>
                        </div>
                        <a href="/spotly/detail/<?= trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($r['nama_cafe'])), '-'); ?>" class="detail-btn">
    Lihat Detail
</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

</div>

<script>
let current = 0;

const gambar = [
<?php
foreach($gambar as $g){
    echo "'/spotly/assets/img/$g',";
}
?>
];

function pilihFoto(src){

    document.getElementById("fotoBesar").src = src;
    current = gambar.indexOf(src);
}

function gantiFoto(arah){
    current += arah;

    if(current >= gambar.length){
        current = 0;
    }

    if(current < 0){
        current = gambar.length - 1;
    }

    document.getElementById("fotoBesar").src = gambar[current];
}

function bukaFormUlasan(){
    const f = document.getElementById('formUlasan');
    f.style.display = 'block';
    f.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function tutupFormUlasan(){
    document.getElementById('formUlasan').style.display = 'none';
}
</script>

</body>
</html>