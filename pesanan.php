
<?php
include 'Koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pesanan = $_POST['id_pesanan'] ?? '';
    $id_pelanggan = $_POST['id_pelanggan'] ?? '';
    $tanggal = $_POST['tanggal_pesanan'] ?? '';
    $status = $_POST['status'] ?? '';

    if (empty($id_pesanan) || empty($id_pelanggan) || empty($tanggal) || empty($status)) {
        echo "Semua kolom wajib diisi.";
        exit;
    }

    // insert ke database
    $stmt = $koneksi->prepare("INSERT INTO pesanan (id_pesanan, id_pelanggan, tanggal_pesanan, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id_pesanan, $id_pelanggan, $tanggal, $status);

    if ($stmt->execute()) {
        echo "Pesanan berhasil ditambahkan.<br><a href='list.php'>Lihat Daftar Pesanan</a>";
    } else {
        echo "Gagal menambahkan pesanan: " . $stmt->error;
    }

    $stmt->close();
    $koneksi->close();
} else 

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Pesanan</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* {
  margin: 0; padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', Tahoma, sans-serif;
}
body {
  background: #ffffffff;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}
canvas#particles {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  z-index: 0;
}
.form-wrapper {
  width: 100%;
  max-width: 380px;
  perspective: 800px;
  z-index: 1;
}
.form-card {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px);
  padding: 20px 18px;
  border-radius: 14px;
  box-shadow: 0 12px 30px rgba(0,0,0,0.5);
  border: 1px solid rgba(148, 163, 184, 0.15);
  transition: transform 0.15s ease, box-shadow 0.2s ease;
  animation: fadeIn 0.8s ease;
}
.form-card:hover {
  box-shadow: 0 18px 40px rgba(196, 59, 14, 0.25);
}
@keyframes fadeIn {
  from {opacity:0; transform:translateY(20px) scale(0.97);}
  to {opacity:1; transform:translateY(0) scale(1);}
}
.form-card h1 {
  text-align: center;
  font-size: 20px;
  color: #121111ff;
  margin-bottom: 18px;
}
.form-card h1 i {
  color: #d33;
  margin-right: 6px;
}
.input-group {margin-bottom: 14px;}
.input-group label {
  display: block;
  margin-bottom: 4px;
  color: #000000ff;
  font-size: 13px;
}
.input-group label i {
  margin-right: 5px;
  color: #d33;
}
input, select {
  width: 100%;
  padding: 9px 11px;
  border: 1px solid rgba(160, 183, 218, 0.25);
  border-radius: 8px;
  background: rgba(228, 228, 231, 0.6);
  font-size: 13px;
  color: #000000ff;
  transition: 0.25s;
}
input:focus, select:focus {
  border-color: #d33;
  box-shadow: 0 0 8px rgba(211, 98, 27, 0.4);
  background: rgba(230, 231, 232, 0.85);
  outline: none;
}
button {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  background: linear-gradient(90deg, #d33, rgba(214, 101, 56, 1));
  border: none;
  border-radius: 8px;
  color: #fff;
  cursor: pointer;
  transition: 0.3s;
}
button:hover {
  background: linear-gradient(90deg, rgba(214, 44, 44, 1), #c77056ff);
  box-shadow: 0 0 15px rgba(255, 111, 0, 0.63);
}
.link-container {
  margin-top: 15px;
  text-align: center;
}
.link-container a {
  color: #e2661eff;
  font-size: 13px;
  text-decoration: none;
}
</style>
</head>
<body>
<canvas id="particles"></canvas>
<div class="form-wrapper">
  <div class="form-card" id="card">
    <h1><i class="fas fa-box"></i> Sistem Pesanan</h1>
    <form id="orderForm" action="insert.php" method="post">
      <div class="input-group">
        <label for="id_pesanan"><i class="fas fa-id-badge"></i> ID Pesanan</label>
        <input type="text" id="id_pesanan" name="id_pesanan" required>
      </div>
      <div class="input-group">
        <label for="id_pelanggan"><i class="fas fa-user"></i> ID Pelanggan</label>
        <input type="text" id="id_pelanggan" name="id_pelanggan" required>
      </div>
      <div class="input-group">
        <label for="tanggal_pesanan"><i class="fas fa-calendar-alt"></i> Tanggal Pesanan</label>
        <input type="date" id="tanggal_pesanan" name="tanggal_pesanan" required>
      </div>
      <div class="input-group">
        <label for="status"><i class="fas fa-clipboard-check"></i> Status</label>
        <select id="status" name="status" required>
          <option value="">-- Pilih Status --</option>
          <option value="canceled">Canceled</option>
          <option value="diproses">Diproses</option>
          <option value="dikirim">Dikirim</option>
          <option value="selesai">Selesai</option>
        </select>
      </div>
      <button type="submit"><i class="fas fa-paper-plane"></i> Kirim Pesanan</button>
    </form>
    <div class="link-container">
      <a href="daftar.php"><i class="fas fa-list"></i> Lihat Daftar Pesanan</a>
    </div>
  </div>
</div>
<script>
// Particle effect
const canvas=document.getElementById("particles"),ctx=canvas.getContext("2d");
canvas.width=innerWidth; canvas.height=innerHeight;
let particlesArray=[];
class Particle {
  constructor(x,y,s,sx,sy){this.x=x;this.y=y;this.size=s;this.sx=sx;this.sy=sy;}
  update(){this.x+=this.sx;this.y+=this.sy;if(this.size>0.2)this.size-=0.02;}
  draw(){ctx.fillStyle="#f30707ff";ctx.beginPath();ctx.arc(this.x,this.y,this.size,0,Math.PI*2);ctx.fill();}
}
function init(){particlesArray=[];for(let i=0;i<60;i++){let s=Math.random()*2+1,x=Math.random()*canvas.width,y=Math.random()*canvas.height,sx=Math.random()*0.6-0.3,sy=Math.random()*0.6-0.3;particlesArray.push(new Particle(x,y,s,sx,sy));}}
function animate(){ctx.clearRect(0,0,canvas.width,canvas.height);particlesArray.forEach(p=>{p.update();p.draw();});requestAnimationFrame(animate);}
init();animate();
</script>
</body>
</html>
