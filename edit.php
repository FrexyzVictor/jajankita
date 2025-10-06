<?php
include "koneksi.php";

// Ambil id dari URL
if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}
$id_menu = $_GET['id'];

// Ambil data menu berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM menu WHERE id_menu='$id_menu'");
$data = mysqli_fetch_assoc($query);

// Jika form disubmit
if (isset($_POST['update'])) {
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];

    // Cek upload gambar baru
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $folder = "uploads/" . $gambar;

        move_uploaded_file($tmp, $folder);

        $update = mysqli_query($koneksi, "UPDATE menu SET 
            nama_menu='$nama_menu', 
            harga='$harga', 
            kategori='$kategori', 
            gambar='$gambar' 
            WHERE id_menu='$id_menu'");
    } else {
        $update = mysqli_query($koneksi, "UPDATE menu SET 
            nama_menu='$nama_menu', 
            harga='$harga', 
            kategori='$kategori' 
            WHERE id_menu='$id_menu'");
    }

    if ($update) {
        echo "<script>alert('Data berhasil diupdate'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update data');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        .form-container {
            width: 400px;
            background: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: orange;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 8px 0 5px;
        }
        input[type="text"], 
        input[type="number"], 
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 5px;
        }
        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background: orange;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: darkorange;
        }
        .btn{
           margin-top: 15px;
            width: 100%;
            padding: 10px;
            background-color: #e9a72dff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
       .btn:hover {
            background: darkorange;
        }

        .preview {
            margin-top: 10px;
            text-align: center;
        }
        .preview img {
            width: 120px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="form-container">
        <h2>Edit Menu</h2>
        <form method="post" enctype="multipart/form-data">
            <label>Nama Menu:</label>
            <input type="text" name="nama_menu" value="<?= $data['nama_menu']; ?>" required>

            <label>Harga:</label>
            <input type="number" name="harga" value="<?= $data['harga']; ?>" required>

            <label>Kategori:</label>
            <input type="text" name="kategori" value="<?= $data['kategori']; ?>" required>

            <label>Gambar:</label>
            <input type="file" name="gambar">
            <div class="preview">
                <?php if (!empty($data['gambar'])) { ?>
                    <img src="uploads/<?= $data['gambar']; ?>">
                <?php } ?>
            </div>
            <br>
            <button type="submit" name="update"  >Update</button><br>
            <a href="data_menu.php" class="btn">Batal</a>

            
        </form>
    </div>
</body>
</html>
