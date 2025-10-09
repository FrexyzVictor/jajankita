<?php
include "koneksi.php";
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
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
    body::-webkit-scrollbar { display: none; }
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
        height:180px;
        object-fit:contain;
        background:#fafafa;
        padding:6px;
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

    <!-- KATEGORI SNACK -->
    <?php
    $sql_snack = "SELECT id_menu, nama_menu, harga, kategori, gambar FROM menu WHERE kategori='Snack' ORDER BY nama_menu LIMIT 4";
    $result_snack = $koneksi->query($sql_snack);
    
    if ($result_snack && $result_snack->num_rows > 0) {
    ?>
    <div class="kategori-header purple">
        <img src="logo 1.png" alt="Logo" class="logo-kategori" />
        <span>Snack</span>
    </div>
    <div class="kategori-desc">
        Nikmati pilihan snack terbaik kami, dibuat khusus untuk memanjakan lidah Anda.
    </div>

    <div class="menu-grid">
        <?php
        $row1 = $result_snack->fetch_assoc();
        if ($row1) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row1['gambar']; ?>" alt="<?php echo $row1['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row1['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row1['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row1['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        
        $row2 = $result_snack->fetch_assoc();
        if ($row2) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row2['gambar']; ?>" alt="<?php echo $row2['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row2['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row2['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row2['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        
        $row3 = $result_snack->fetch_assoc();
        if ($row3) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row3['gambar']; ?>" alt="<?php echo $row3['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row3['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row3['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row3['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        
        $row4 = $result_snack->fetch_assoc();
        if ($row4) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row4['gambar']; ?>" alt="<?php echo $row4['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row4['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row4['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row4['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
    <?php
    }
    ?>

    <?php
    $sql_minuman = "SELECT id_menu, nama_menu, harga, kategori, gambar FROM menu WHERE kategori='Minuman' ORDER BY nama_menu LIMIT 4";
    $result_minuman = $koneksi->query($sql_minuman);
    
    if ($result_minuman && $result_minuman->num_rows > 0) {
    ?>
    <div class="kategori-header green">
        <img src="logo 1.png" alt="Logo" class="logo-kategori" />
        <span>Minuman</span>
    </div>
    <div class="kategori-desc">
        Nikmati pilihan minuman terbaik kami, dibuat khusus untuk memanjakan lidah Anda.
    </div>

    <div class="menu-grid">
        <?php
        $row1 = $result_minuman->fetch_assoc();
        if ($row1) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row1['gambar']; ?>" alt="<?php echo $row1['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row1['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row1['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row1['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        
        $row2 = $result_minuman->fetch_assoc();
        if ($row2) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row2['gambar']; ?>" alt="<?php echo $row2['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row2['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row2['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row2['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        
        $row3 = $result_minuman->fetch_assoc();
        if ($row3) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row3['gambar']; ?>" alt="<?php echo $row3['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row3['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row3['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row3['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        
        $row4 = $result_minuman->fetch_assoc();
        if ($row4) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row4['gambar']; ?>" alt="<?php echo $row4['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row4['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row4['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row4['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
    <?php
    }
    ?>

    <?php
    $sql_makanan = "SELECT id_menu, nama_menu, harga, kategori, gambar FROM menu WHERE kategori='Makanan Ringan' ORDER BY nama_menu LIMIT 4";
    $result_makanan = $koneksi->query($sql_makanan);
    
    if ($result_makanan && $result_makanan->num_rows > 0) {
    ?>
    <div class="kategori-header orange">
        <img src="logo 1.png" alt="Logo" class="logo-kategori" />
        <span>Makanan Ringan</span>
    </div>
    <div class="kategori-desc">
        Nikmati pilihan makanan ringan terbaik kami, dibuat khusus untuk memanjakan lidah Anda.
    </div>

    <div class="menu-grid">
        <?php
        $row1 = $result_makanan->fetch_assoc();
        if ($row1) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row1['gambar']; ?>" alt="<?php echo $row1['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row1['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row1['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row1['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        
        $row2 = $result_makanan->fetch_assoc();
        if ($row2) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row2['gambar']; ?>" alt="<?php echo $row2['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row2['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row2['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row2['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        
        $row3 = $result_makanan->fetch_assoc();
        if ($row3) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row3['gambar']; ?>" alt="<?php echo $row3['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row3['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row3['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row3['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        
        $row4 = $result_makanan->fetch_assoc();
        if ($row4) {
        ?>
        <div class="menu-card">
            <img src="uploads/<?php echo $row4['gambar']; ?>" alt="<?php echo $row4['nama_menu']; ?>">
            <div class="menu-info">
                <h3><?php echo $row4['nama_menu']; ?></h3>
                <div class="harga">Rp <?php echo number_format($row4['harga'], 0, ',', '.'); ?></div>
                <form action="proses_pesan.php" method="POST">
                    <input type="hidden" name="id_menu" value="<?php echo $row4['id_menu']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
    <?php
    }
    ?>

</div>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.promo-slider .slide');
function showSlide(idx){
    slides.forEach((s,i)=> s.classList.toggle('active', i===idx));
}
setInterval(()=>{ currentSlide=(currentSlide+1)%slides.length; showSlide(currentSlide); }, 3500);
</script>

<?php
$koneksi->close();
?>
</body>
</html>