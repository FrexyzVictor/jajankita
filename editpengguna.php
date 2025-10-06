<?php
include 'koneksi.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: kelola_pengguna.php");
    exit;
}

$id = intval($_GET['id']);
$result = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan=$id");
if (mysqli_num_rows($result) == 0) {
    echo "Data tidak ditemukan.";
    exit;
}
$data = mysqli_fetch_assoc($result);

// Update data jika form disubmit
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $pw = mysqli_real_escape_string($koneksi, $_POST['pw']);
    $id_kota = intval($_POST['id_kota']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);

    // Jika password diubah
    if (!empty($pw)) {
        $query = "UPDATE pelanggan SET 
                    nama='$nama',
                    username='$username',
                    pw='$pw',
                    status='$status',
                    id_kota='$id_kota',
                    alamat='$alamat',
                    no_telepon='$no_telepon'
                  WHERE id_pelanggan=$id";
    } else {
        $query = "UPDATE pelanggan SET 
                    nama='$nama',
                    username='$username',
                    status='$status',
                    id_kota='$id_kota',
                    alamat='$alamat',
                    no_telepon='$no_telepon'
                  WHERE id_pelanggan=$id";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: kelola_pengguna.php?msg=updated");
        exit;
    } else {
        echo "❌ Gagal update data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
    <div class="container">
        <h2>Edit Pengguna</h2>
        <form method="POST" class="row g-3">
            <div class="col-md-4">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
            </div>
            <div class="col-md-4">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" required>
            </div>
            <div class="col-md-4">
                <label>Password (isi jika ingin ubah)</label>
                <input type="password" name="pw" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Status</label>
                <select name="status" class="form-select" required>
                    <option value="pegawai" <?= $data['status']=='pegawai'?'selected':'' ?>>Pegawai</option>
                    <option value="pengguna" <?= $data['status']=='pengguna'?'selected':'' ?>>Pelanggan</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Kota</label>
                <select name="id_kota" class="form-select" required>
                    <?php
                    $qkota = mysqli_query($koneksi, "SELECT * FROM kota ORDER BY nama_kota ASC");
                    while ($k = mysqli_fetch_assoc($qkota)):
                    ?>
                        <option value="<?= $k['id_kota'] ?>" <?= $data['id_kota']==$k['id_kota']?'selected':'' ?>>
                            <?= htmlspecialchars($k['nama_kota']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($data['alamat']) ?>" required>
            </div>
            <div class="col-md-4">
                <label>No Telepon</label>
                <input type="text" name="no_telepon" class="form-control" value="<?= htmlspecialchars($data['no_telepon']) ?>" required>
            </div>
            <div class="col-12 text-end">
                <button type="submit" name="update" class="btn btn-warning">Update</button>
                <a href="kelolapengguna.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
