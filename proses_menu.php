<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_menu = $_POST['nama_menu'];
    $harga     = $_POST['harga'];
    $kategori  = $_POST['kategori'];
    $gambar    = $_FILES['gambar']['name'];
    $tmp_name  = $_FILES['gambar']['tmp_name'];

    if (!empty($nama_menu) && !empty($harga) && !empty($kategori)) {

        // Proses upload jika ada gambar
        $nama_file = "";
        if (!empty($gambar)) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir); // Buat folder jika belum ada
            }

            $ext = pathinfo($gambar, PATHINFO_EXTENSION);
            $nama_file = uniqid() . "." . $ext;
            $target_file = $target_dir . $nama_file;

            move_uploaded_file($tmp_name, $target_file);
        }

        // Simpan data ke database
        $stmt = $koneksi->prepare("INSERT INTO menu (nama_menu, harga, kategori, gambar) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $nama_menu, $harga, $kategori, $nama_file);

        if ($stmt->execute()) {
            echo "<script>
                alert('Menu berhasil disimpan!');
                window.location.href = 'data_menu.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menyimpan data!');
                window.location.href = 'input_menu.php';
            </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
            alert('Semua field wajib diisi!');
            window.location.href = 'input_menu.php';
        </script>";
    }
}
?>
