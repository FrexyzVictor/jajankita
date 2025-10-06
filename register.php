<?php
include "koneksi.php";

if (isset($_POST['daftar'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = ($_POST['password']); //md5
    $role = "User"; // default user

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
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registrasi</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      padding: 0;
      overflow-x: hidden;
      position: relative;
      background: linear-gradient(270deg, #ff7207ff, #bd8816ff, #f16710ff, #a45d07ff);
      background-size: 800% 800%;
      animation: gradientBG 20s ease infinite;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Bubble background */
    .bubble {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      animation: float 10s infinite;
      z-index: 0;
    }
    .bubble:nth-child(1) { width: 80px; height: 80px; top: 20%; left: 10%; animation-duration: 12s; }
    .bubble:nth-child(2) { width: 50px; height: 50px; top: 40%; left: 70%; animation-duration: 15s; }
    .bubble:nth-child(3) { width: 100px; height: 100px; top: 65%; left: 20%; animation-duration: 18s; }
    .bubble:nth-child(4) { width: 60px; height: 60px; top: 75%; left: 80%; animation-duration: 20s; }

    @keyframes float {
      0% { transform: translateY(0) scale(1); opacity: 0.6; }
      50% { transform: translateY(-60px) scale(1.1); opacity: 1; }
      100% { transform: translateY(0) scale(1); opacity: 0.6; }
    }

    /* Navbar */
    nav {
      width: 100%;
      background: rgba(255, 255, 255, 0.12);
      padding: 12px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      backdrop-filter: blur(8px);
      position: relative;
      z-index: 1;
    }

    nav .logo {
      font-size: 18px;
      font-weight: bold;
      color: #fff;
      letter-spacing: 1px;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    nav ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      transition: 0.4s ease;
      position: relative;
      padding: 5px 8px;
    }

    nav ul li a::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: -5px;
      width: 0%;
      height: 2px;
      background: #ffd900ff;
      transition: width 0.4s ease;
    }

    nav ul li a:hover {
      color: #fff;
      transform: scale(1.05);
    }

    nav ul li a:hover::after {
      width: 100%;
    }

    /* Register Box */
    .register-box {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 20px;
      padding: 40px 30px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 8px 32px rgba(31, 38, 135, 0.3);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.18);
      animation: fade-in 1s ease;
      margin: 30px 0;
      position: relative;
      z-index: 1;
    }

    @keyframes fade-in {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .register-box h2 {
      text-align: center;
      color: #fff;
      margin-bottom: 30px;
    }

    .input-group {
      position: relative;
      margin-bottom: 20px;
    }

    .input-group i {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #ccc;
    }

    .input-group input {
      width: 100%;
      padding: 12px 12px 12px 40px;
      border-radius: 10px;
      border: none;
      outline: none;
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
      transition: 0.3s;
    }

    .input-group input::placeholder {
      color: #ddd;
    }

    .input-group input:focus {
      background-color: rgba(255, 255, 255, 0.3);
    }

    .register-box input[readonly] {
      background-color: rgba(255, 255, 255, 0.1);
      color: #aaa;
    }

    /* Button */
    .btn {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      border: none;
      background: linear-gradient(135deg, #e17e0bff, #d4ae04ff);
      color: #fff;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: rgba(255,255,255,0.3);
      transition: left 0.4s ease;
    }

    .btn:hover::before { left: 100%; }
    .btn:hover {
      background: linear-gradient(135deg, #c96705ff, #e67615ff);
      transform: scale(1.05);
      box-shadow: 0 0 15px rgba(108, 99, 255, 0.6);
    }

    /* Footer solid box */
    footer {
      width: 100%;
      display: flex;
      justify-content: center;
      padding: 25px 20px;
      margin-top: auto;
      position: relative;
      z-index: 1;
    }

    .footer-box {
      background: #f1f1f1ff;
      color: #030303ff;
      border-radius: 16px;
      padding: 25px 30px;
      text-align: center;
      max-width: 700px;
      width: 100%;
      box-shadow: 0 6px 18px rgba(0,0,0,0.4);
    }

    .footer-box h3 {
      margin-bottom: 12px;
      font-size: 18px;
      font-weight: 600;
    }

    .footer-box p {
      font-size: 14px;
      color: #1e1d1dff;
      margin-bottom: 18px;
    }

    .social-links {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 15px;
    }

    .social-links a {
      color: #151515ff;
      font-size: 22px;
      animation: socialAnim 6s infinite linear;
    }

    /* Animasi berganti warna semua logo */
    @keyframes socialAnim {
      0% { color: #fff; }
      20% { color: #E4405F; }   /* Instagram pink */
      40% { color: #0088cc; }   /* Telegram biru */
      60% { color: #5865F2; }   /* Discord ungu */
      80% { color: #1DA1F2; }   /* X biru muda */
      100% { color: #fff; }
    }

    .footer-box small {
      font-size: 12px;
      color: #393737ff;
    }
  </style>
</head>
<body>
  <!-- Bubble background -->
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>

  <!-- Navbar -->
  <nav>
    <div class="logo"><i class="fas fa-shield-alt"></i> Sign-up</div>
    <ul>
      <li><a href="register.php">Register</a></li>
      <li><a href="Login.php">Login</a></li>
    </ul>
  </nav>

  <!-- Register Box -->
  <div class="register-box">
    <h2><i class="fas fa-user-plus"></i> Registrasi</h2>
    <form action="" method="post">
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" id="username" name="username" placeholder="Username" required>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" id="password" name="password" placeholder="Password" required>
      </div>
      <button type="submit" name="daftar" class="btn">Daftar</button>
    </form>
  </div>

  <!-- Footer -->
  <footer>
    <div class="footer-box">
      <h3>Tentang Admin Register</h3>
      <p>Sistem Registrasi ini dibuat untuk Mengelola Data Pengguna dengan Aman dan Cepat. Admin bertugas Memverifikasi serta Menjaga Keamanan Database.</p>
      <div class="social-links">
        <a href="https://www.instagram.com/mochamadbintanglaksamana?igsh=MXdnb3NmemtvZ3dkMg=="><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-telegram"></i></a>
        <a href="#"><i class="fab fa-discord"></i></a>
        <a href="#"><i class="fab fa-x-twitter"></i></a>
      </div>
      <small>© JajanKitaCommunityProGammer | 2025 SecureApp. All Rights Reserved.</small>
    </div>
  </footer>
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
