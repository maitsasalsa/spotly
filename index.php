<?php

session_start();

include 'app/controllers/HomeController.php';

?>

<!DOCTYPE html>
<html>
<head>
    <base href="/spotly/">
    <title>Spotly</title>
    <link rel="stylesheet" href="/spotly/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">SPOTLY</div>

    <div class="menu-container">
        <button class="menu-btn" onclick="toggleMenu()"><i class="bi bi-list"></i></button>

        <div class="dropdown" id="dropdownMenu">

            <?php if(isset($_SESSION['login'])): ?>

                <div class="dropdown-user">
                    <small>Masuk sebagai admin</small>
                    <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
                </div>

                <a href="pages/cs.php">Customer Service</a>

                <div class="dropdown-divider"></div>

                <a href="/spotly/logout.php" class="dropdown-logout"
                   onclick="return confirm('Yakin ingin logout sebagai admin?');">Logout Admin</a>

            <?php elseif(isset($_SESSION['customer_login'])): ?>

                <div class="dropdown-user">
                    <small>Masuk sebagai</small>
                    <strong><?= htmlspecialchars($_SESSION['customer_username']); ?></strong>
                </div>

                <a href="/spotly/favorit">Favoritku</a>
                <a href="pages/cs.php">Customer Service</a>

                <div class="dropdown-divider"></div>

                <a href="/spotly/customer_logout.php" class="dropdown-logout"
                   onclick="return confirm('☕ Terima kasih sudah berkunjung ke Spotly.\n\nYakin ingin logout?');">Logout</a>

            <?php else: ?>

                <a href="/spotly/login.php">Login Admin</a>
                <a href="/spotly/customer_login.php">Login Customer</a>

                <div class="dropdown-divider"></div>

                <a href="pages/cs.php">Customer Service</a>

            <?php endif; ?>

        </div>
    </div>
</nav>

<section class="cafe">
    <div class="overlay">

        <h1>Temukan Cafe Terbaik di UNNES </h1>

        <p>
            Cari cafe aesthetic, tempat nugas,
            dan hidden gems favoritmu
        </p>

        <form onsubmit="return cariCafe(event)">

    <input
        type="text"
        id="keyword"
        name="keyword"
        placeholder="Cari cafe..."
        autocomplete="off"
        value="<?= $_GET['keyword'] ?? ''; ?>"
    >

    <div id="suggestion-box"></div>

    <button type="submit">
        Cari
    </button>

</form>

    </div>
</section>

<div class="sort-bar">

    <h2>Daftar Cafe</h2>

   <div class="filter-area">

   <label class="chip-buka">
    <input type="checkbox"
           <?= $bukaSekarang ? 'checked' : '' ?>
           onchange="toggleBuka(this.checked)">
    <span>Buka sekarang</span>
</label>

    <label>Urutkan Berdasarkan:</label>

    <div class="custom-select">

        <button class="select-btn" onclick="toggleSelect()">
                <?= $label ?> <i class="bi bi-chevron-down"></i>
        </button>

        <div class="select-menu" id="selectMenu">
           <div onclick="urutkan('rating_tertinggi')">Rating Tertinggi</div>
<div onclick="urutkan('rating_terendah')">Rating Terendah</div>
<div onclick="urutkan('harga_termurah')">Harga Termurah</div>
<div onclick="urutkan('harga_tertinggi')">Harga Tertinggi</div>
<div onclick="urutkan('nama')">Nama A-Z</div>
            </div>
        </div>

    </div>

</div>


<div class="hasil-search">
    <i class="bi bi-search"></i> Ditemukan <?= mysqli_num_rows($query); ?> cafe
</div>

<div class="cafe-container">

<?php while($row = mysqli_fetch_assoc($query)){ ?>

<?php
$foto = $cafe->getFotoCafe($row['id_cafe']);
?>

<div class="card">

    <div class="card-image">

        <button type="button"
                class="fav-btn <?= in_array($row['id_cafe'], $favoritSaya) ? 'aktif' : ''; ?>"
                onclick="toggleFavorit(this, <?= $row['id_cafe']; ?>)"
                aria-label="Simpan ke favorit"><i class="bi bi-heart"></i><i class="bi bi-heart-fill"></i></button>

        <button class="prev-btn" onclick="geser(<?= $row['id_cafe']; ?>,-1)">
            <i class="bi bi-chevron-left"></i>
        </button>

        <?php
        $no = 0;
        while($f = mysqli_fetch_assoc($foto)){
        ?>
            <img
                src="/spotly/assets/img/<?= $f['nama_foto']; ?>"
                class="slide slide<?= $row['id_cafe']; ?>"
                <?= $no==0 ? 'style="display:block"' : '' ?>
            >
        <?php
            $no++;
        }
        ?>

        <button class="next-btn" onclick="geser(<?= $row['id_cafe']; ?>,1)">
            <i class="bi bi-chevron-right"></i>
        </button>

    </div>

    <div class="card-info">
        <h2><?= $row['nama_cafe']; ?></h2>

        <p class="alamat">
            <i class="bi bi-geo-alt-fill"></i> <?= $row['alamat']; ?>
        </p>

        <p class="deskripsi">
            <?= $row['deskripsi']; ?>
        </p>
    </div>

    <div class="card-side">

        <div class="rating-wrap">
            <div class="rating">
                <i class="bi bi-star-fill"></i> <?= $row['rating']; ?> <small>Google</small>
            </div>
            <div class="rating-spotly">
                <?php if($row['jumlah_spotly'] > 0): ?>
                    Spotly <i class="bi bi-star-fill"></i> <?= $row['rata_spotly']; ?>
                    <span>(<?= $row['jumlah_spotly']; ?>)</span>
                <?php else: ?>
                    <span>Belum ada ulasan</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="harga">
            Mulai dari <br>
            Rp <?= number_format($row['harga_min'],0,',','.'); ?>
        </div>

        <a href="detail/<?= trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($row['nama_cafe'])), '-'); ?>">
            <button class="detail-btn">Lihat Detail</button>
        </a>

    </div>

</div>

<?php } ?>

</div>
<script>

function toggleMenu() {
    document.getElementById("dropdownMenu").classList.toggle("show");
}

function toggleSelect() {
    document.getElementById("selectMenu").classList.toggle("show-select");
}

let current = {};

function geser(id, arah){

    let slides =
    document.querySelectorAll(".slide"+id);

    if(!current[id]){
        current[id] = 0;
    }

    slides[current[id]].style.display = "none";

    current[id] += arah;

    if(current[id] >= slides.length){
        current[id] = 0;
    }

    if(current[id] < 0){
        current[id] = slides.length - 1;
    }

    slides[current[id]].style.display = "block";
}

let activeIndex = -1;

const keywordInput  = document.getElementById("keyword");
const suggestionBox = document.getElementById("suggestion-box");

function setActive(index){
    const items = suggestionBox.children;

    for(let i = 0; i < items.length; i++){
        items[i].classList.remove("active");
    }

    if(index >= 0 && index < items.length){
        items[index].classList.add("active");
        items[index].scrollIntoView({ block: "nearest" });
    }

    activeIndex = index;
}

keywordInput.addEventListener("keydown", function(e){

    const items = suggestionBox.children;
    if(!items.length) return;

    if(e.key === "ArrowDown"){
        e.preventDefault();
        setActive((activeIndex + 1) % items.length);
    }
    else if(e.key === "ArrowUp"){
        e.preventDefault();
        setActive((activeIndex - 1 + items.length) % items.length);
    }
    else if(e.key === "Enter" && activeIndex >= 0){
        e.preventDefault();
        items[activeIndex].click();
        activeIndex = -1;
    }
    else if(e.key === "Escape"){
        suggestionBox.innerHTML = "";
        activeIndex = -1;
    }
});

keywordInput.addEventListener("keyup", function(e){

    // tombol navigasi tidak boleh memicu pencarian ulang
    if(["ArrowDown", "ArrowUp", "Enter", "Escape"].includes(e.key)) return;

    activeIndex = -1;

    let keyword = this.value;

    if(keyword.length < 1){
        suggestionBox.innerHTML = "";
        return;
    }

    fetch("app/controllers/SearchController.php?keyword=" + encodeURIComponent(keyword))
    .then(res => res.text())
    .then(data => {
        suggestionBox.innerHTML = data;
    });

});

function toggleFavorit(btn, idCafe){
    fetch('app/controllers/FavoritController', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id_cafe=' + idCafe
    })
    .then(res => {
        if(res.status === 401){
            window.location = '/spotly/customer_login.php';
            return null;
        }
        return res.json();
    })
    .then(data => {
        if(data){ btn.classList.toggle('aktif', data.favorit); }
    });
}

function pilihCafe(nama){

    document.getElementById("keyword").value = nama;

    document.getElementById("suggestion-box").innerHTML = "";

}

function cariCafe(e){

    e.preventDefault();

    let keyword =
    document.getElementById("keyword").value;

    keyword = keyword
        .toLowerCase()
        .replace(/\s+/g, '-');

    window.location = awalanUrl() + "/search/" + keyword;

}

// Awalan URL: "/spotly/buka" kalau filter aktif, "/spotly" kalau tidak
function awalanUrl(){
    return /^\/spotly\/buka(\/|$)/.test(window.location.pathname)
        ? '/spotly/buka' : '/spotly';
}

function urutkan(jenis){
    window.location = awalanUrl() + '/sort/' + jenis;
}

function toggleBuka(aktif){
    // buang awalan buka (kalau ada), lalu pasang lagi bila filter dinyalakan
    let path = window.location.pathname.replace(/^\/spotly\/buka/, '/spotly');
    if(aktif){
        path = path.replace(/^\/spotly/, '/spotly/buka');
    }
    window.location = path;
}

</script>

</body>
</html>