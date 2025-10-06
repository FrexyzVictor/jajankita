<?php
include "koneksi.php";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = ($_POST['password']); //md5
    $role = "user"; // default user

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah terdaftar!";
    } else {
        $sql = "INSERT INTO users (username, password, role) VALUES ('$username','$password','$role')";
        if (mysqli_query($koneksi, $sql)) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Terjadi kesalahan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JajanKita - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4" data-aos="zoom-in">
                <div class="logo-text"><img src="jajan.jpeg"  alt="Logo" width="50%"></div>
                <h4 class="text-center mb-4">Daftar Akun Baru</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Buat username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Buat password" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-custom w-100">Daftar</button>
                </form>
                <p class="mt-3 text-center text-muted">Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="script.js"></script>
<?php if(!empty($error)): ?>
<script> showError("<?= $error ?>"); </script>
<?php endif; ?>
<?php if(!empty($success)): ?>
<script> showSuccess("<?= $success ?>", "login.php"); </script>
<?php endif; ?>
</body>
</html>