<?php
include 'koneksi.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Kelola Pengguna</title>
     <style>
          .navbar-brand {
               font-weight: bold;
               font-size: 1.5rem;
          }

          .navbar-nav .nav-link:hover,
          .dropdown-menu .dropdown-item:hover {
               background: rgb(195, 115, 17);
               border-radius: 6px;
          }

          .dropdown-menu {
               min-width: 160px;
          }

          .navbar-nav {
               text-align: center;
          }

          body {
               font-family: Arial, sans-serif;
               background: #f4f4f4;
               margin: 0;
               padding: 20px;
          }

          .container {
               max-width: 900px;
               margin: auto;
               background: #fff;
               padding: 20px;
               border-radius: 8px;
               box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
          }

          .navbar {
               background: rgb(236, 139, 21);
          }

          h1 {
               text-align: center;
          }

          .add-user {
               margin-bottom: 20px;
               text-align: right;
          }

          .add-user button {
               padding: 10px 15px;
               background: #28a745;
               color: white;
               border: none;
               border-radius: 5px;
               cursor: pointer;
          }

          table {
               width: 100%;
               border-collapse: collapse;
          }

          table thead {
               background: rgb(236, 139, 21);
               color: white;
          }

          table th,
          table td {
               padding: 12px;
               text-align: left;
               border-bottom: 1px solid #ddd;
          }

          table tbody tr:hover {
               background-color: #f1f1f1;
          }

          .action-buttons button {
               margin-right: 5px;
               padding: 5px 10px;
               border: none;
               border-radius: 4px;
               cursor: pointer;
          }

          .edit-btn {
               background-color: #ffc107;
               color: black;
               text-decoration: none;
               border-radius: 4px;
               padding: 5px 10px;
               margin-right: 5px;
          }

          .delete-btn {
               background-color: #dc3545;
               color: white;
               text-decoration: none;
               border-radius: 4px;
               padding: 5px 10px;
               margin-right: 5px;
          }
     </style>
     <?php
     $message = "";
     // Tambah Pelanggan
     if (isset($_POST['add'])) {
          $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
          $username = mysqli_real_escape_string($koneksi, $_POST['username']);
          $status = mysqli_real_escape_string($koneksi, $_POST['status']);
          $pw = mysqli_real_escape_string($koneksi, $_POST['pw']);
          $id_kota = intval($_POST['id_kota']);
          $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
          $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);


          $cek = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE username='$username'");
          if (mysqli_num_rows($cek) > 0) {
               $message = "❌ Username sudah digunakan.";
          } else {
               $insert = mysqli_query($koneksi, "INSERT INTO pelanggan (nama, username, pw, status, id_kota, alamat, no_telepon) VALUES ('$nama', '$username', '$pw', '$status', '$id_kota', '$alamat', '$no_telepon')");
               $message = $insert ? "✅ Pelanggan berhasil ditambahkan." : "❌ Gagal menambahkan Pelanggan.";
          }
     }

     // Hapus Pelanggan
     if (isset($_GET['delete'])) {
          $id = intval($_GET['delete']);
          $hapus = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id_pelanggan = $id");
          $message = $hapus ? "✅ Pelanggan berhasil dihapus." : "❌ Gagal menghapus Pelanggan.";
     }

     

     //Join Tabel
     $result = mysqli_query($koneksi, "
    SELECT p.*, k.nama_kota 
    FROM pelanggan p
    LEFT JOIN kota k ON p.id_kota = k.id_kota
    ORDER BY p.id_pelanggan DESC
     ");

     ?>
</head>

<body>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

     <nav class="navbar navbar-expand-lg navbar-dark">
          <div class="container-fluid">
               <a class="navbar-brand" href="#"><img src="logo 1.png" width="20%"></a>

               <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                    <span class="navbar-toggler-icon"></span>
               </button>

               <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                         <li class="nav-item"><a class="nav-link" href="beranda.php">Beranda</a></li>
                         <li class="nav-item"><a class="nav-link" href="input_makanan.php">Input Makanan</a></li>
                         <li class="nav-item"><a class="nav-link" href="kelola_makanan.php">Kelola Makanan</a></li>
                         <li class="nav-item"><a class="nav-link active" href="kelola_pengguna.php">Kelola Pengguna</a></li>
                         <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                   <?= $_SESSION['pegawai']['nama_pegawai'] ?? 'Pengguna'; ?>
                              </a>
                              <ul class="dropdown-menu dropdown-menu-end">
                                   <li><a class="dropdown-item" href="profil.php">Profil</a></li>
                                   <li>
                                        <hr class="dropdown-divider">
                                   </li>
                                   <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                              </ul>
                         </li>
                    </ul>
               </div>
          </div>
     </nav>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

     <div class="container">
          <h1>Kelola Pengguna</h1>

          <div class="add-user">
               <?php if ($message): ?>
                    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
               <?php endif; ?>

               <div class="card mb-4">
                    <div class="card-header">Tambah Penguna Baru</div>
                    <div class="card-body">
                         <form method="POST" class="row g-3">
                              <div class="col-md-3"><input type="text" name="nama" class="form-control" placeholder="Nama Pengguna" required /></div>
                              <div class="col-md-3"><input type="text" name="username" class="form-control" placeholder="Username" required /></div>
                              <div class="col-md-3"><input type="password" name="pw" class="form-control" placeholder="Password" required /></div>
                              <div class="col-md-3">
                                   <select name="status" class="form-select" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="pegawai">Pegawai</option>
                                        <option value="pengguna">Pelanggan</option>
                                   </select>
                              </div>
                              <div class="col-md-3">
                                   <select name="id_kota" class="form-select" required>
                                        <option value="">-- Pilih Kota --</option>
                                        <?php
                                        $qkota = mysqli_query($koneksi, "SELECT * FROM kota ORDER BY nama_kota ASC");
                                        while ($k = mysqli_fetch_assoc($qkota)):
                                        ?>
                                             <option value="<?= $k['id_kota'] ?>"><?= htmlspecialchars($k['nama_kota']) ?></option>
                                        <?php endwhile; ?>
                                   </select>
                              </div>
                              <div class="col-md-3"><input type="text" name="alamat" class="form-control" placeholder="Alamat" required /></div>
                              <div class="col-md-3"><input type="text" name="no_telepon" class="form-control" placeholder="No Telepon" required /></div>

                              <div class="col-12 text-end">
                                   <button type="submit" name="add" class="btn btn-success">Tambah Pengguna</button>
                              </div>
                         </form>
                    </div>
               </div>
          </div>

          <table>
     <thead>
          <tr>
               <th>No</th>
               <th>Nama</th>
               <th>Username</th>
               <th>Status</th>
               <th>Alamat</th>
               <th>Kota</th>
               <th>No Telefon</th>
               <th>Aksi</th>
          </tr>
     </thead>
     <tbody>
          <?php
          $no = 1;
          while ($row = mysqli_fetch_assoc($result)):
          ?>
               <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td><?= htmlspecialchars($row['nama_kota'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                    <td class="action-buttons">
                         <a href="editpengguna.php?id=<?= $row['id_pelanggan'] ?>" class="edit-btn">Edit</a>
                         <a href="kelolapengguna.php?delete=<?= $row['id_pelanggan'] ?>" class="delete-btn" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</a>
                    </td>
               </tr>
          <?php endwhile; ?>
     </tbody>
</table>

     </div>
</body>

</html>