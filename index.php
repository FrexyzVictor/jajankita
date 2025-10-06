<?php
include "koneksi.php";
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT id_menu, nama_menu, harga, kategori FROM menu ORDER BY kategori, nama_menu";
$res = $koneksi->query($sql);

$menus = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $menus[$row['kategori']][] = $row;
    }
} else {
    die("Query gagal: " . $koneksi->error);
}
$koneksi->close();

/* fungsi gambar */
function img_path_for(array $menu): ?string {
    $baseDir = __DIR__ . '/img/';
    $webBase = 'img/';
    $exts    = ['png','jpg','jpeg','webp','gif'];

    // aturan khusus
    $nama = strtolower(trim($menu['nama_menu']));
    if ($nama === 'es jeruk') {
        return $webBase . 'es_jeruk.jpg';
    }
    if ($nama === 'es teh manis') {
        return $webBase . 'es_tehmanis.jpg';
    }

    // cek berdasar id_menu
    foreach ($exts as $e) {
        $file = $baseDir . $menu['id_menu'] . '.' . $e;
        if (file_exists($file)) {
            return $webBase . $menu['id_menu'] . '.' . $e;
        }
    }

    // cek slug nama
    $slug = strtolower(preg_replace('/[^a-z0-9]/i', '', $menu['nama_menu']));
    foreach ($exts as $e) {
        $file = $baseDir . $slug . '.' . $e;
        if (file_exists($file)) {
            return $webBase . $slug . '.' . $e;
        }
    }

    return null; // fallback
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jajankita</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
    body { font-family:'Segoe UI',sans-serif;background:#f0f2f5;margin:0;padding:0; }
    .container { max-width:1100px;margin:auto;padding:30px;overflow-x:hidden; }

    /* SLIDER PROMO */
    .promo-slider {
        width:100%;max-width:1100px;margin:20px auto;
        position:relative;overflow:hidden;border-radius:12px;
        height:260px;background:#fff;
    }
    .promo-slider .slides { position:relative;width:100%;height:100%; }
    .promo-slider .slide { width:100%;height:100%;position:absolute;left:0;top:0;opacity:0;transition:opacity .6s; }
    .promo-slider .slide.active { opacity:1;position:relative; }
    .promo-slider img { width:100%;height:100%;object-fit:cover; }

    /* Kategori */
    .kategori-header {
        padding:10px 15px;font-size:18px;font-weight:700;
        border-radius:6px;margin:30px 0 15px 0;
        display:flex;align-items:center;gap:18px;
        width:100%;box-sizing:border-box;color:#fff;
    }
    .kategori-header.orange { background:linear-gradient(to right,#ff7e5f,#feb47b); }
    .kategori-header.green { background:linear-gradient(to right,#43e97b,#38f9d7); }
    .kategori-header.purple { background:linear-gradient(to right,#6a11cb,#2575fc); }
    .kategori-header img.logo-kategori { max-height:40px; }

    .kategori-desc { font-size:14px;color:#555;margin-bottom:12px; }

    /* Grid menu */
    .menu-grid { display:grid;grid-template-columns:repeat(2,1fr);gap:15px; }
    @media(max-width:700px){ .menu-grid{ grid-template-columns:1fr; } }

    .menu-card {
        background:#fff;border-radius:8px;box-shadow:0 3px 8px rgba(0,0,0,.1);
        overflow:hidden;transition:transform .2s;
        display:flex;flex-direction:column;align-items:center;
    }
    .menu-card:hover { transform:translateY(-5px); }

    /* gambar menu */
    .menu-card img {
        width:100%;
        height:180px;           /* tinggi seragam */
        object-fit:contain;     /* proporsional, tidak terpotong */
        background:#fafafa;     /* latar belakang netral */
        padding:6px;            /* biar ada jarak */
        border-bottom:1px solid #eee;
        transition:transform .3s;
    }
    .menu-card:hover img { transform:scale(1.05); }

    /* info menu */
    .menu-info { width:calc(100% - 10px);padding:10px;text-align:center;font-size:14px; }
    .menu-info h3 { margin:0 0 5px;font-size:15px; }
    .harga { font-weight:700;color:#28a745;font-size:14px;margin-bottom:5px; }
    form { margin-top:5px; }
    input[type=number] { width:45px;padding:4px;margin-right:4px;text-align:center;border:1px solid #ddd;border-radius:4px;font-size:13px; }
    button {
        background:#28a745;border:none;color:#fff;padding:5px 8px;
        border-radius:4px;cursor:pointer;font-size:13px;
    }
    button:hover { background:#218838; }


    /* Tombol Lihat Selengkapnya */
    .lihat-selengkapnya {
        display:inline-block;margin-top:12px;padding:8px 14px;
        background:#007bff;color:#fff;text-decoration:none;
        border-radius:6px;font-size:14px;font-weight:600;
        transition:background .2s;
    }
    .lihat-selengkapnya:hover { background:#0056b3; }
</style>
</head>
<body>

<!-- SLIDER PROMO -->
<div class="promo-slider">
    <div class="slides">
        <div class="slide active"><img src="img/iklan5.jpg" alt="Promo 1"></div>
        <div class="slide"><img src="img/iklan2.jpg" alt="Promo 2"></div>
        <div class="slide"><img src="img/iklan3.jpg" alt="Promo 3"></div>
    </div>
</div>

<div class="container">
<?php foreach ($menus as $kategori => $items): 
    $kategoriLower = strtolower($kategori);
    $warna = '';
    if ($kategoriLower==='makanan ringan') $warna='orange';
    elseif ($kategoriLower==='minuman') $warna='green';
    elseif ($kategoriLower==='snack') $warna='purple';
?>
    <div class="kategori-header <?= $warna ?>">
        <img src="logo 1.png" alt="Logo" class="logo-kategori" />
        <span><?= htmlspecialchars(ucwords($kategori)) ?></span>
    </div>
    <div class="kategori-desc">
        Nikmati pilihan <?= htmlspecialchars($kategori) ?> terbaik kami, dibuat khusus untuk memanjakan lidah Anda.
    </div>

    <div class="menu-grid">
    <?php foreach ($items as $menu): 
        $img_path = img_path_for($menu);
        if (!$img_path) continue;
    ?>
        <div class="menu-card">
            <img src="<?= htmlspecialchars($img_path) ?>" alt="<?= htmlspecialchars($menu['nama_menu']) ?>">
            <div class="menu-info">
                <h3><?= htmlspecialchars($menu['nama_menu']) ?></h3>
                <div class="harga">Rp <?= number_format((float)$menu['harga'],0,',','.') ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?= (int)$menu['id_menu'] ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit"><a href="Pesanan.php">Pesan</a></button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

    <?php if (count($items) >= 4): ?>
        <a href="kategori.php?jenis=<?= urlencode($kategori) ?>" class="lihat-selengkapnya">Lihat Selengkapnya</a>
    <?php endif; ?>

<?php endforeach; ?>
</div>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.promo-slider .slide');
function showSlide(idx){
    slides.forEach((s,i)=> s.classList.toggle('active', i===idx));
}
setInterval(()=>{ currentSlide=(currentSlide+1)%slides.length; showSlide(currentSlide); }, 3500);
</script>
</body>
</html>
