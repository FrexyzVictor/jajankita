<?php
include 'Koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Pesanan</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* {margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, sans-serif;}
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
.wrapper {
  width: 100%;
  max-width: 750px;
  z-index: 1;
}
.card {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px);
  border-radius: 14px;
  padding: 20px;
  box-shadow: 0 12px 30px rgba(0,0,0,0.5);
  border: 1px solid rgba(148, 163, 184, 0.15);
  animation: fadeIn 0.8s ease;
}
.card h1 {
  text-align: center;
  font-size: 22px;
  color: rgba(14, 13, 13, 1);
  margin-bottom: 15px;
}
.card h1 i {
  color: #d33;
  margin-right: 6px;
}
.table-container {
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}
thead {
  background: rgba(225, 94, 71, 0.15);
}
thead th {
  padding: 10px;
  text-align: left;
  color: #d33;
  font-weight: bold;
}
tbody tr {
  background: rgba(241, 239, 239, 0.6);
  border-bottom: 1px solid rgba(224, 146, 68, 0.45);
  transition: background 0.2s;
}
tbody tr:hover {
  background: rgba(56, 189, 248, 0.15);
}
tbody td {
  padding: 8px 10px;
  color: #3d3e3fff;
}
.status {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: bold;
  text-transform: capitalize;
}
.status.diproses {background: rgba(250, 204, 21, 0.2); color: #facc15;}
.status.dikirim {background: rgba(34, 197, 94, 0.2); color: #22c55e;}
.status.selesai {background: rgba(59, 130, 246, 0.2); color: #3b82f6;}
.status.canceled {background: rgba(239, 68, 68, 0.2); color: #ef4444;}
.link-container {
  margin-top: 15px;
  text-align: center;
}
.link-container a {
  color: #f85b38ff;
  font-size: 13px;
  text-decoration: none;
}
@keyframes fadeIn {
  from {opacity:0; transform:translateY(20px) scale(0.97);}
  to {opacity:1; transform:translateY(0) scale(1);}
}
</style>
</head>
<body>
<canvas id="particles"></canvas>
<div class="wrapper">
  <div class="card">
    <h1><i class="fas fa-list"></i> Daftar Pesanan</h1>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID Pesanan</th>
            <th>ID Pelanggan</th>
            <th>Tanggal</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Narkoba</td>
            <td>Arya Milito</td>
            <td>2025-08-10</td>
            <td><span class="status diproses">diproses</span></td>
          </tr>
          <tr>
            <td>Ganja</td>
            <td>Prima al Rasyid</td>
            <td>2025-08-11</td>
            <td><span class="status dikirim">dikirim</span></td>
          </tr>
          <tr>
            <td>Sendok Makan</td>
            <td>Muhaimin</td>
            <td>2025-08-12</td>
            <td><span class="status selesai">selesai</span></td>
          </tr>
          <tr>
            <td>Lil Lil Bahlil</td>
            <td>Mochamad Bintang Laksamana S</td>
            <td>2025-08-12</td>
            <td><span class="status canceled">canceled</span></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="link-container">
      <a href="pesanan.php"><i class="fas fa-arrow-left"></i> Kembali ke Form</a>
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
  draw(){ctx.fillStyle="#cd1f1fff";ctx.beginPath();ctx.arc(this.x,this.y,this.size,0,Math.PI*2);ctx.fill();}
}
function init(){particlesArray=[];for(let i=0;i<60;i++){let s=Math.random()*2+1,x=Math.random()*canvas.width,y=Math.random()*canvas.height,sx=Math.random()*0.6-0.3,sy=Math.random()*0.6-0.3;particlesArray.push(new Particle(x,y,s,sx,sy));}}
function animate(){ctx.clearRect(0,0,canvas.width,canvas.height);particlesArray.forEach(p=>{p.update();p.draw();});requestAnimationFrame(animate);}
init();animate();
</script>
</body>
</html>
