<?php
session_start();
include "koneksi.php";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = ($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'Admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - JajanKita</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{
    min-height: 100vh;
    max-height: 100vh; /* membatasi tinggi agar tidak melebihi viewport */
    width: 100%;
    max-width: 100vw; /* membatasi lebar agar tidak overflow */
    margin: 0 auto;
    overflow-x: hidden; /* mencegah horizontal scroll */
    background: linear-gradient(135deg,#ff7f50,#ff9a3c,#ffb347,#ffd700);
    background-size:400% 400%;
    animation: gradientMove 12s ease infinite;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center; /* login box selalu di tengah vertikal */
    padding: 20px;
}

@keyframes gradientMove{0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

/* Navbar transparan */
.navbar{
    position: fixed; top:0; left:0; width:100%;
    padding:15px 30px; background: rgba(255,255,255,0.1);
    display:flex; justify-content:space-between; align-items:center;
    z-index:10; border-bottom:1px solid rgba(255,255,255,0.2);
}
.navbar h1{font-size:18px;color:#fff;font-weight:600;letter-spacing:1px;}
.nav-links{display:flex;gap:20px;}
.nav-links a{color:#fff;text-decoration:none;font-size:14px;font-weight:500;transition:0.3s;}
.nav-links a:hover{color:#ffd700;}

/* Bubbles */
.bubbles{position:absolute;width:100%;height:100%;top:0;left:0;overflow:hidden;z-index:0;}
.bubbles span{position:absolute;bottom:-150px;background:rgba(255,255,255,0.3);border-radius:50%;animation:rise 15s infinite ease-in;}
.bubbles span:nth-child(1){left:25%;width:80px;height:80px;animation-delay:0s;}
.bubbles span:nth-child(2){left:10%;width:40px;height:40px;animation-delay:2s;}
.bubbles span:nth-child(3){left:70%;width:100px;height:100px;animation-delay:4s;}
.bubbles span:nth-child(4){left:50%;width:60px;height:60px;animation-delay:6s;}
.bubbles span:nth-child(5){left:85%;width:30px;height:30px;animation-delay:8s;}
@keyframes rise{0%{transform:translateY(0) scale(1);}100%{transform:translateY(-1200px) scale(1.2);}}

/* Login Box modern */
.login-box{
    box-sizing: border-box;
    position: relative; 
    z-index:1;
    background:#fff;
    border-radius:25px;
    padding:50px 30px;
    width: 100%;
    max-width:400px;
    margin:auto;
    margin-top: calc(50vh - 220px);
    box-shadow:0 20px 50px rgba(0,0,0,0.25);
    border:2px solid rgba(255,255,255,0.2);
    animation:fadeInBox 1s ease forwards;
    text-align:center;
}
@keyframes fadeInBox{from{opacity:0;transform:translateY(40px);}to{opacity:1;transform:translateY(0);}}
.logo-text img{
    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:15px;
    margin-bottom:25px;
    border:2px solid #ff7f50;
}

/* Inputs */
.input-group{position:relative;margin-bottom:20px;}
.input-group i{position:absolute;top:50%;left:12px;transform:translateY(-50%);color:#999;font-size:16px;}
.input-group input{
    width:100%;
    padding:15px 15px 15px 45px;
    border-radius:15px;
    border:1px solid #ccc;
    outline:none;
    background-color:#fff;
    color:#333;
    font-size:15px;
    transition:0.3s;
}
.input-group input:focus{
    border:2px solid #ff7f50;
    box-shadow:0 0 15px rgba(255,127,80,0.4);
}

/* Button */
.login-box input[type="submit"]{
    width:100%;
    padding:15px;
    border:none;
    background:linear-gradient(135deg,#ff7f50,#ff9a3c);
    color:#fff;
    font-weight:600;
    border-radius:15px;
    cursor:pointer;
    font-size:16px;
    transition:all 0.4s ease;
}
.login-box input[type="submit"]:hover{
    background:linear-gradient(135deg,#ff6347,#ff8c00);
    transform:scale(1.05);
    box-shadow:0 10px 25px rgba(255,99,71,0.4);
}

/* Links */
.login-box p{color:#333;font-size:14px;margin-top:18px;}
.login-box a{color:#ff7f50;font-weight:500;text-decoration:none;}
.login-box a:hover{text-decoration:underline;}

/* Footer solid oranye */
.footer{
    background:#ff7f50;
    color:#fff;
    margin-top:auto;
    padding:40px 20px 20px;
    border-top-left-radius:25px;
    border-top-right-radius:25px;
    box-shadow:0 -5px 20px rgba(0,0,0,0.3);
    min-height:100px;
    max-height:350px;
    position:relative;
    transition:height 0.25s ease-in-out;
}
.resize-handle{
    position:absolute;
    top:8px;
    left:50%;
    transform:translateX(-50%);
    width:60px;height:6px;
    background:#fff;
    border-radius:3px;
    cursor:ns-resize;
    transition:background 0.3s;
}
.resize-handle:hover{background:#ffd700;}
.footer-container{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:25px;margin-bottom:25px;}
.footer-box h3{margin-bottom:15px;font-size:18px;color:#fff;}
.footer-box ul{list-style:none;padding:0;}
.footer-box ul li{margin-bottom:8px;}
.footer-box ul li a{text-decoration:none;color:#fff;font-size:14px;transition:0.3s;}
.footer-box ul li a:hover{color:#ffd700;font-weight:600;}
.footer-about{text-align:center;margin-top:20px;color:#fff;font-size:14px;line-height:1.6;padding:0 10px;}
.footer-bottom{text-align:center;margin-top:25px;padding-top:15px;border-top:1px solid rgba(255,255,255,0.4);font-size:13px;color:#fff;}
.social-links{margin-top:12px;display:flex;gap:12px;justify-content:center;}
.social-links a{display:inline-flex;justify-content:center;align-items:center;width:38px;height:38px;border-radius:50%;background:#fff;color:#ff7f50;font-size:18px;transition:0.3s;}
.social-links a:hover{background:#ffd700;color:#fff;transform:scale(1.1);}

/* Responsive */
@media(max-width:480px){
    .login-box{padding:40px 20px;margin-top:80px;}
    .navbar h1{font-size:16px;}
    .nav-links{gap:12px;}
}
</style>
</head>
<body>

<div class="navbar">
    <h1>JajanKita</h1>
    <div class="nav-links">
        <a href="login.php.php">Home</a>
        <a href="#">About</a>
        <a href="#">Services</a>
        <a href="#">Contact</a>
        <a href="#">Help</a>
    </div>
</div>

<div class="bubbles">
    <span></span><span></span><span></span><span></span><span></span>
</div>

<div class="login-box">
    <div class="logo-text">
        <img src="jajan.jpeg" alt="Logo">
    </div>
    <form method="POST">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
        </div>
        <input type="submit" name="login" value="Sign In">
    </form>
    <p>Don’t have an account? <a href="register.php">Register here</a></p>
</div>
<script>
// Modern footer resize with smooth animation
const footer = document.getElementById("footer");
const resizeHandle = document.getElementById("resizeHandle");
let isResizing = false;
let startY, startHeight;

resizeHandle.addEventListener("mousedown", e => {
    isResizing = true;
    startY = e.clientY;
    startHeight = footer.offsetHeight;
    footer.style.transition = "height 0.2s ease";
    document.body.style.userSelect = "none";
});

window.addEventListener("mousemove", e => {
    if (!isResizing) return;
    let dy = startY - e.clientY;
    let newHeight = startHeight + dy;
    newHeight = Math.min(Math.max(newHeight, 100), 350);
    footer.style.height = newHeight + "px";
});

window.addEventListener("mouseup", e => {
    isResizing = false;
    document.body.style.userSelect = "auto";
    footer.style.transition = "height 0.25s ease"; // smooth back to normal
});

// Toggle password with animation
const passwordInput = document.getElementById("password");
const toggleHTML = '<span class="toggle-password" id="togglePassword"><i class="fas fa-eye-slash"></i></span>';
passwordInput.insertAdjacentHTML('afterend', toggleHTML);
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("click", () => {
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    togglePassword.classList.toggle("active");
    togglePassword.querySelector("i").classList.toggle("fa-eye");
    togglePassword.querySelector("i").classList.toggle("fa-eye-slash");
});
</script>

<?php if(!empty($error)): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({icon:"error",title:"Login Gagal",text:"<?= $error ?>",confirmButtonColor:"#d33"});
</script>
<?php endif; ?>

</body>
</html>
