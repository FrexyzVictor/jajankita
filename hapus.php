<?php
include "koneksi.php";

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}

$id_menu = $_GET['id'];

// Hapus gambar dulu
$q = mysqli_query($koneksi, "SELECT gambar FROM menu WHERE id_menu='$id_menu'");
$d = mysqli_fetch_assoc($q);
if ($d && !empty($d['gambar']) && file_exists("uploads/".$d['gambar'])) {
    unlink("uploads/".$d['gambar']);
}

// Hapus data menu
$hapus = mysqli_query($koneksi, "DELETE FROM menu WHERE id_menu='$id_menu'");

if ($hapus) {
    echo "<script>alert('Data berhasil dihapus'); window.location='data_menu.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data'); window.location='data_menu.php';</script>";
}
?>
