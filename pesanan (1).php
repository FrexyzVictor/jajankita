<?php
include 'AsoleleKoneksiWKWKWKWKKWK.php';

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
    $stmt = $koneksi->prepare("INSERT INTO wkwkwkkwk (id_pesanan, id_pelanggan, tanggal_pesanan, status) VALUES (?, ?, ?, ?)");
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
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}


body {
  background: radial-gradient(circle at top left, #0f172a, #1e293b 60%);
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.form-wrapper {
  width: 100%;
  max-width: 500px;
}

.form-card {
  background: rgba(30, 41, 59, 0.8);
  backdrop-filter: blur(12px);
  padding: 35px 30px;
  border-radius: 18px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.4);
  animation: fadeIn 0.6s ease;
  border: 1px solid rgba(148, 163, 184, 0.2);
}

.form-card h1 {
  text-align: center;
  font-size: 26px;
  color: #f8fafc;
  margin-bottom: 30px;
  font-weight: 700;
}

.form-card h1 i {
  color: #38bdf8;
  margin-right: 8px;
}

.input-group {
  margin-bottom: 20px;
}

.input-group label {
  display: block;
  margin-bottom: 6px;
  color: #cbd5e1;
  font-weight: 600;
}

.input-group label i {
  margin-right: 6px;
  color: #38bdf8;
}

input[type="text"],
input[type="date"],
select {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid rgba(148, 163, 184, 0.3);
  border-radius: 10px;
  background: rgba(15, 23, 42, 0.6);
  font-size: 14px;
  color: #f1f5f9;
  transition: 0.3s ease;
}

input:focus,
select:focus {
  border-color: #38bdf8;
  background-color: rgba(15, 23, 42, 0.8);
  outline: none;
  box-shadow: 0 0 10px rgba(56, 189, 248, 0.4);
}

button[type="submit"] {
  width: 100%;
  padding: 12px;
  background: linear-gradient(90deg, #38bdf8, #2563eb);
  border: none;
  color: #fff;
  font-size: 16px;
  border-radius: 10px;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.15s ease;
}

button[type="submit"] i {
  margin-right: 6px;
}

button[type="submit"]:hover {
  background: linear-gradient(90deg, #0ea5e9, #1d4ed8);
  transform: translateY(-2px);
}

.link-container {
  margin-top: 25px;
  text-align: center;
}

.link-container a {
  color: #38bdf8;
  text-decoration: none;
  font-weight: 600;
}

.link-container a i {
  margin-right: 6px;
}

.link-container a:hover {
  text-decoration: underline;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
</head>
<body>
  <div class="form-wrapper">
    <div class="form-card">
      <h1><i class="fas fa-box"></i> Sistem Pesanan</h1>
      <form action="insert.php" method="post">
        
        <div class="input-group">
          <label for="id_pesanan"><i class="fas fa-id-badge"></i> ID Pesanan</label>
          <input type="text" id="id_pesanan" name="id_pesanan" placeholder="Contoh: PSN001" required>
        </div>

        <div class="input-group">
          <label for="id_pelanggan"><i class="fas fa-user"></i> ID Pelanggan</label>
          <input type="text" id="id_pelanggan" name="id_pelanggan" placeholder="Contoh: PLG123" required>
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
        <a href="pesanan.php"><i class="fas fa-list"></i> Lihat Daftar Pesanan</a>
      </div>
    </div>
  </div>
</body>
</html>
