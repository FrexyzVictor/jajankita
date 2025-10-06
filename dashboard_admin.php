<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil username dari session
$namaAdmin = $_SESSION['username'];

// Simpan waktu login (format: 2025-10-05 22:15:30)
$now = date("Y-m-d H:i:s");

// Update status dan waktu login user yang sedang aktif
mysqli_query($koneksi, "UPDATE users SET status='online', login_time='$now' WHERE username='$namaAdmin'");

// Ambil semua data user
$query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id ASC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard</title>
	<link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	<style>
		/* ===== ROOT ===== */
		:root {
			--primary: #FF6600;
			--primary-dark: #CC5200;
			--light: #fff;
			--grey: #F5F7FB;
			--dark: #333;
			--shadow: rgba(0, 0, 0, 0.1);
			--orange-light: #FFE5D4;
			--orange-glow: rgba(255, 102, 0, 0.15);
			--accent-yellow: #FFC107;
			--accent-pink: #FF6B9D;
		}

		/* ===== GENERAL ===== */
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Poppins', sans-serif;
		}

		body {
			background: var(--grey);
			color: var(--dark);
			overflow-x: hidden;
			position: relative;
		}

		/* BACKGROUND DECORATIVE PATTERN */
		body::before {
			content: '';
			position: fixed;
			top: 0;
			left: 260px;
			width: calc(100% - 260px);
			height: 100%;
			background-image: 
				radial-gradient(circle at 20% 50%, rgba(255, 102, 0, 0.03) 0%, transparent 50%),
				radial-gradient(circle at 80% 80%, rgba(255, 193, 7, 0.04) 0%, transparent 50%);
			pointer-events: none;
			z-index: 0;
		}

		a { text-decoration: none; }
		ul { list-style: none; }

		/* ===== SIDEBAR ===== */
		#sidebar {
			position: fixed;
			left: 0;
			top: 0;
			width: 260px;
			height: 100%;
			background: linear-gradient(180deg, var(--primary), var(--primary-dark));
			color: var(--light);
			transition: all 0.4s ease;
			box-shadow: 4px 0 20px var(--shadow);
			z-index: 100;
			overflow-y: auto;
		}

		#sidebar::-webkit-scrollbar { width: 6px; }
		#sidebar::-webkit-scrollbar-thumb {
			background: rgba(255,255,255,0.3);
			border-radius: 10px;
		}

		#sidebar.hide { width: 70px; }

		#sidebar .brand {
			display: flex;
			align-items: center;
			height: 64px;
			font-size: 22px;
			font-weight: 700;
			padding: 0 16px;
			background: rgba(255, 255, 255, 0.15);
			backdrop-filter: blur(6px);
		}

		#sidebar .brand .logo {
			height: 50px;
			width: auto;
			object-fit: contain;
			margin-right: 10px;
		}

		#sidebar .side-menu { padding: 20px 16px; }

		#sidebar .side-menu li.divider {
			margin: 20px 0 10px;
			padding: 8px 12px;
			font-size: 11px;
			font-weight: 600;
			letter-spacing: 1px;
			opacity: 0.7;
		}

		#sidebar .side-menu li a {
			display: flex;
			align-items: center;
			justify-content: space-between;
			color: var(--light);
			padding: 12px;
			border-radius: 10px;
			transition: 0.3s;
			position: relative;
		}

		#sidebar .side-menu li a .icon {
			font-size: 20px;
			margin-right: 10px;
		}

		#sidebar .side-menu li a:hover {
			background: rgba(255, 255, 255, 0.25);
			transform: translateX(6px);
		}

		#sidebar .side-menu li a.active {
			background: var(--light);
			color: var(--primary);
			font-weight: 600;
			box-shadow: 0 4px 12px rgba(0,0,0,0.25);
		}

		#sidebar .side-menu .side-dropdown {
			padding-left: 30px;
			max-height: 0;
			overflow: hidden;
			transition: max-height 0.3s ease;
		}

		#sidebar .side-menu li:hover .side-dropdown {
			max-height: 300px;
		}

		/* ===== CONTENT ===== */
		#content {
			position: relative;
			left: 260px;
			width: calc(100% - 260px);
			transition: all 0.4s ease;
			min-height: 100vh;
		}

		#sidebar.hide + #content {
			left: 70px;
			width: calc(100% - 70px);
		}

		/* ===== NAVBAR ===== */
		nav {
			height: 64px;
			background: var(--light);
			padding: 0 20px;
			display: flex;
			align-items: center;
			gap: 20px;
			box-shadow: 0 2px 10px var(--shadow);
			position: sticky;
			top: 0;
			z-index: 50;
		}

		nav .toggle-sidebar {
			font-size: 22px;
			color: var(--primary);
			cursor: pointer;
			transition: 0.3s;
		}

		nav .toggle-sidebar:hover {
			color: var(--primary-dark);
			transform: rotate(90deg);
		}

		nav .form-group {
			position: relative;
			flex: 1;
			max-width: 400px;
		}

		nav .form-group input {
			width: 100%;
			border: none;
			background: var(--grey);
			padding: 10px 40px 10px 15px;
			border-radius: 20px;
			outline: none;
		}

		nav .form-group input:focus {
			box-shadow: 0 0 8px var(--primary);
		}

		nav .form-group .icon {
			position: absolute;
			right: 15px;
			top: 50%;
			transform: translateY(-50%);
			color: var(--primary);
		}

		nav .nav-link {
			position: relative;
			color: var(--dark);
			font-size: 20px;
			padding: 8px;
		}

		nav .nav-link .badge {
			position: absolute;
			top: 0;
			right: 0;
			background: var(--primary);
			color: white;
			font-size: 10px;
			padding: 2px 6px;
			border-radius: 10px;
		}

		nav .divider {
			width: 1px;
			height: 30px;
			background: #ddd;
		}

		nav .profile {
			position: relative;
			cursor: pointer;
		}

		nav .profile img {
			width: 40px;
			height: 40px;
			border-radius: 50%;
			object-fit: cover;
			border: 2px solid var(--primary);
		}

		nav .profile-link {
			position: absolute;
			top: calc(100% + 10px);
			right: 0;
			background: white;
			box-shadow: 0 4px 12px var(--shadow);
			border-radius: 10px;
			padding: 10px;
			min-width: 180px;
			opacity: 0;
			pointer-events: none;
			transition: 0.3s;
		}

		nav .profile:hover .profile-link {
			opacity: 1;
			pointer-events: all;
		}

		nav .profile-link li a {
			display: flex;
			align-items: center;
			gap: 10px;
			padding: 10px;
			color: var(--dark);
			border-radius: 8px;
			transition: 0.3s;
		}

		nav .profile-link li a:hover {
			background: var(--grey);
			color: var(--primary);
		}

		/* ===== MAIN ===== */
		main {
			padding: 30px;
			position: relative;
			z-index: 1;
		}

		/* GEOMETRIC DECORATIONS IN MAIN */
		main::before {
			content: '';
			position: absolute;
			top: 100px;
			right: 50px;
			width: 80px;
			height: 80px;
			background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 102, 0, 0.1));
			transform: rotate(45deg);
			border-radius: 10px;
			pointer-events: none;
			z-index: -1;
		}

		main::after {
			content: '';
			position: absolute;
			bottom: 150px;
			left: 80px;
			width: 60px;
			height: 60px;
			background: linear-gradient(135deg, rgba(255, 107, 157, 0.08), rgba(255, 102, 0, 0.08));
			border-radius: 50%;
			pointer-events: none;
			z-index: -1;
		}

		/* ===== WELCOME BOX WITH DECORATIONS ===== */
		.welcome-box {
			position: relative;
			background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
			color: white;
			padding: 40px;
			border-radius: 20px;
			margin-bottom: 30px;
			overflow: hidden;
			box-shadow: 0 10px 30px rgba(255, 102, 0, 0.3);
		}

		/* Original Decorative Blobs */
		.welcome-box::before {
			content: '';
			position: absolute;
			width: 200px;
			height: 200px;
			background: rgba(255, 255, 255, 0.1);
			border-radius: 50%;
			top: -80px;
			right: -50px;
		}

		.welcome-box::after {
			content: '';
			position: absolute;
			width: 150px;
			height: 150px;
			background: rgba(255, 255, 255, 0.08);
			border-radius: 50%;
			bottom: -60px;
			left: 50px;
		}

		/* NEW: Additional decorative elements */
		.welcome-box .deco-circle {
			position: absolute;
			border-radius: 50%;
			background: var(--orange-light);
			opacity: 0.2;
			pointer-events: none;
		}

		.welcome-box .deco-circle:nth-child(1) {
			width: 120px;
			height: 120px;
			top: 20px;
			right: 100px;
		}

		.welcome-box .deco-circle:nth-child(2) {
			width: 80px;
			height: 80px;
			bottom: 40px;
			right: 200px;
		}

		.welcome-box .deco-circle:nth-child(3) {
			width: 60px;
			height: 60px;
			top: 50%;
			left: 15%;
		}

		/* NEW: Dotted pattern decoration */
		.welcome-box .deco-dots {
			position: absolute;
			top: 30px;
			left: 40px;
			width: 100px;
			height: 100px;
			background-image: radial-gradient(circle, rgba(255, 255, 255, 0.15) 2px, transparent 2px);
			background-size: 15px 15px;
			opacity: 0.6;
			pointer-events: none;
			z-index: 1;
		}

		/* NEW: Wave decoration */
		.welcome-box .deco-wave {
			position: absolute;
			bottom: 0;
			right: 0;
			width: 200px;
			height: 60px;
			background: rgba(255, 255, 255, 0.05);
			border-radius: 50% 50% 0 0;
			pointer-events: none;
			z-index: 1;
		}

		.welcome-box h2 {
			position: relative;
			z-index: 2;
			font-size: 28px;
			margin-bottom: 10px;
		}

		.welcome-box p {
			position: relative;
			z-index: 2;
			font-size: 16px;
			opacity: 0.95;
		}

		/* ===== BREADCRUMBS ===== */
		.title {
			font-size: 28px;
			font-weight: 700;
			color: var(--dark);
			margin-bottom: 10px;
		}

		.breadcrumbs {
			display: flex;
			align-items: center;
			gap: 8px;
			margin-bottom: 30px;
			font-size: 14px;
		}

		.breadcrumbs li a {
			color: var(--primary);
			transition: 0.3s;
		}

		.breadcrumbs li a:hover {
			text-decoration: underline;
		}

		.breadcrumbs .divider {
			color: #999;
		}

		/* ===== INFO CARDS WITH DECORATIONS ===== */
		.info-data {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
			gap: 20px;
			margin-bottom: 30px;
		}

		.info-data .card {
			position: relative;
			background: var(--light);
			padding: 25px;
			border-radius: 15px;
			box-shadow: 0 6px 18px var(--shadow);
			transition: transform 0.3s, box-shadow 0.3s;
			overflow: hidden;
		}

		/* Original decoration */
		.info-data .card::before {
			content: '';
			position: absolute;
			width: 100px;
			height: 100px;
			background: var(--orange-glow);
			border-radius: 50%;
			top: -30px;
			right: -30px;
		}

		/* NEW: Additional corner decoration */
		.info-data .card::after {
			content: '';
			position: absolute;
			width: 50px;
			height: 50px;
			background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), transparent);
			bottom: 0;
			left: 0;
			border-radius: 0 15px 0 0;
		}

		.info-data .card:hover {
			transform: translateY(-8px);
			box-shadow: 0 12px 28px rgba(0,0,0,0.15);
		}

		.info-data .card .head {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			margin-bottom: 20px;
			position: relative;
			z-index: 2;
		}

		.info-data .card .head h2 {
			font-size: 32px;
			font-weight: 700;
			color: var(--primary);
		}

		.info-data .card .head p {
			font-size: 14px;
			color: #666;
			margin-top: 5px;
		}

		.info-data .card .head .icon {
			font-size: 36px;
			color: var(--primary);
			opacity: 0.3;
		}

		.info-data .card .head .icon.down {
			color: #dc3545;
		}

		.info-data .card .progress {
			display: block;
			height: 8px;
			background: var(--grey);
			border-radius: 10px;
			overflow: hidden;
			position: relative;
			z-index: 2;
		}

		.info-data .card .progress::before {
			content: '';
			position: absolute;
			left: 0;
			top: 0;
			height: 100%;
			width: var(--value);
			background: linear-gradient(90deg, var(--primary), var(--primary-dark));
			border-radius: 10px;
		}

		.info-data .card .label {
			display: block;
			margin-top: 8px;
			font-size: 12px;
			color: var(--primary);
			font-weight: 600;
			position: relative;
			z-index: 2;
		}

		/* ===== DATA SECTION ===== */
		.data {
			margin-bottom: 30px;
		}

		.content-data {
			position: relative;
			background: var(--light);
			padding: 25px;
			border-radius: 15px;
			box-shadow: 0 6px 18px var(--shadow);
			overflow: hidden;
		}

		/* Original decoration */
		.content-data::after {
			content: '';
			position: absolute;
			width: 150px;
			height: 150px;
			background: var(--orange-glow);
			border-radius: 50%;
			top: -50px;
			right: 50px;
		}

		/* NEW: Additional decorative line pattern */
		.content-data::before {
			content: '';
			position: absolute;
			bottom: 20px;
			left: 20px;
			width: 100px;
			height: 3px;
			background: linear-gradient(90deg, var(--primary), transparent);
			border-radius: 10px;
			pointer-events: none;
		}

		.content-data .head {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
			position: relative;
			z-index: 2;
		}

		.content-data .head h3 {
			font-size: 20px;
			font-weight: 600;
		}

		.content-data .menu {
			position: relative;
			cursor: pointer;
		}

		.content-data .menu .icon {
			font-size: 24px;
			color: var(--dark);
		}

		.content-data .menu-link {
			position: absolute;
			top: calc(100% + 10px);
			right: 0;
			background: white;
			box-shadow: 0 4px 12px var(--shadow);
			border-radius: 10px;
			padding: 8px;
			min-width: 120px;
			opacity: 0;
			pointer-events: none;
			transition: 0.3s;
			z-index: 10;
		}

		.content-data .menu:hover .menu-link {
			opacity: 1;
			pointer-events: all;
		}

		.content-data .menu-link li a {
			display: block;
			padding: 8px 12px;
			color: var(--dark);
			border-radius: 6px;
			transition: 0.3s;
		}

		.content-data .menu-link li a:hover {
			background: var(--grey);
			color: var(--primary);
		}

		.content-data .chart {
			position: relative;
			z-index: 2;
		}

		/* ===== TABLE WRAPPER WITH DECORATIONS ===== */
		.wrapper {
			position: relative;
		}

		.wrapper .card {
			position: relative;
			background: var(--light);
			padding: 30px;
			border-radius: 15px;
			box-shadow: 0 6px 18px var(--shadow);
			overflow: hidden;
		}

		/* Original decoration */
		.wrapper .card::before {
			content: '';
			position: absolute;
			width: 120px;
			height: 120px;
			background: var(--orange-glow);
			border-radius: 50%;
			bottom: -40px;
			right: 100px;
		}

		/* NEW: Additional decorative elements for table */
		.wrapper .card::after {
			content: '';
			position: absolute;
			width: 70px;
			height: 70px;
			background: radial-gradient(circle, rgba(255, 193, 7, 0.08), transparent);
			top: 40px;
			left: 30px;
			border-radius: 50%;
			pointer-events: none;
		}

		/* NEW: Decorative corner accent */
		.wrapper .card .corner-accent {
			position: absolute;
			top: 0;
			right: 0;
			width: 100px;
			height: 100px;
			background: linear-gradient(135deg, transparent 50%, rgba(255, 102, 0, 0.05) 50%);
			pointer-events: none;
			border-radius: 0 15px 0 0;
		}

		.wrapper .card h2 {
			position: relative;
			z-index: 2;
			font-size: 22px;
			margin-bottom: 20px;
			color: var(--dark);
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.wrapper .card h2 i {
			color: var(--primary);
		}

		/* ===== TABLE ===== */
		.table-admin {
			position: relative;
			z-index: 2;
			overflow-x: auto;
		}

		.table-admin table {
			width: 100%;
			border-collapse: collapse;
			font-size: 14px;
		}

		.table-admin thead {
			background: linear-gradient(135deg, var(--primary), var(--primary-dark));
			color: #fff;
		}

		.table-admin th {
			padding: 15px;
			text-align: left;
			font-weight: 600;
		}

		.table-admin td {
			padding: 15px;
			border-bottom: 1px solid #eee;
		}

		.table-admin tbody tr {
			transition: 0.3s;
		}

		.table-admin tbody tr:hover {
			background: #fff4eb;
		}

		/* NEW: Status badges styling */
		.status-active {
			background: linear-gradient(135deg, #28a745, #20c997);
			color: #fff;
			padding: 6px 12px;
			border-radius: 12px;
			font-size: 12px;
			font-weight: 600;
			display: inline-block;
			box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
		}

		.status-inactive {
			background: linear-gradient(135deg, #dc3545, #c82333);
			color: #fff;
			padding: 6px 12px;
			border-radius: 12px;
			font-size: 12px;
			font-weight: 600;
			display: inline-block;
			box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
		}

		/* ===== RESPONSIVE ===== */
		@media (max-width: 992px) {
			#sidebar { width: 220px; }
			#content {
				left: 220px;
				width: calc(100% - 220px);
			}
			body::before {
				left: 220px;
				width: calc(100% - 220px);
			}
		}

		@media (max-width: 768px) {
			#sidebar {
				left: -260px;
			}
			#sidebar.show {
				left: 0;
			}
			#content {
				width: 100%;
				left: 0;
			}
			body::before {
				left: 0;
				width: 100%;
			}
			main { padding: 20px; }
			.info-data {
				grid-template-columns: 1fr;
			}
		}

		@media (max-width: 576px) {
			.welcome-box {
				padding: 25px;
			}
			.welcome-box h2 {
				font-size: 22px;
			}
			main .title {
				font-size: 22px;
			}
		}
	</style>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="LogoClean.png" alt="JajanKita" class="logo">
		</a>
		<ul class="side-menu">
			<li><a href="#" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
			<li class="divider" data-text="SHOP">SHOP</li>
			<li>
				<a href="#"><i class='bx bxs-inbox icon'></i> Pengelolaan Produk <i class='bx bx-chevron-right icon-right'></i></a>
				<ul class="side-dropdown">
					<li><a href="#">Manage User</a></li>
					<li><a href="#">Reports</a></li>
					<li><a href="#">Invoices</a></li>
					<li><a href="#">Logs</a></li>
				</ul>
			</li>
			<li><a href="#"><i class='bx bxs-chart icon'></i> Charts</a></li>
			<li><a href="#"><i class='bx bxs-widget icon'></i> Widgets</a></li>
			<li class="divider" data-text="TABLE AND FORMS">Table and Forms</li>
			<li><a href="#"><i class='bx bx-table icon'></i> Tables</a></li>
			<li>
				<a href="#"><i class='bx bxs-notepad icon'></i> Forms <i class='bx bx-chevron-right icon-right'></i></a>
				<ul class="side-dropdown">
					<li><a href="#">Basic</a></li>
					<li><a href="#">Select</a></li>
					<li><a href="#">Checkbox</a></li>
					<li><a href="#">Radio</a></li>
				</ul>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu toggle-sidebar'></i>
			<form action="#">
				<div class="form-group">
					<input type="text" placeholder="Search...">
					<i class='bx bx-search icon'></i>
				</div>
			</form>
			<a href="#" class="nav-link">
				<i class='bx bxs-bell icon'></i>
				<span class="badge">5</span>
			</a>
			<a href="#" class="nav-link">
				<i class='bx bxs-message-square-dots icon'></i>
				<span class="badge">8</span>
			</a>
			<span class="divider"></span>
			<div class="profile">
				<img src="uploads/lil bahlil.jpg" alt="Profile">
				<ul class="profile-link">
					<li><a href="#"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
					<li><a href="#"><i class='bx bxs-cog'></i> Settings</a></li>
					<li><a href="logout.php"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
				</ul>
			</div>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<!-- Pesan Selamat Datang -->
			<div class="welcome-box">
				<div class="deco-circle"></div>
				<div class="deco-circle"></div>
				<div class="deco-circle"></div>
				<div class="deco-dots"></div>
				<div class="deco-wave"></div>
				<h2>Selamat Datang di Website Admin Dashboard🎉</h2>
				<p>Halo, <strong><?php echo $namaAdmin; ?></strong>! Senang bertemu denganmu.</p>
			</div>

			<h1 class="title">Dashboard</h1>
			<ul class="breadcrumbs">
				<li><a href="Dashboard_admin.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="index.php" class="active">Dashboard</a></li>
			</ul>

			<!-- Info Cards -->
			<div class="info-data">
				<div class="card">
					<div class="head">
						<div>
							<h2>?</h2>
							<p>Traffic</p>
						</div>
						<i class='bx bx-trending-up icon'></i>
					</div>
					<span class="progress" data-value="40%" style="--value: 40%"></span>
					<span class="label">40%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>?</h2>
							<p>Sales</p>
						</div>
						<i class='bx bx-trending-down icon down'></i>
					</div>
					<span class="progress" data-value="60%" style="--value: 60%"></span>
					<span class="label">60%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>?</h2>
							<p>Pageviews</p>
						</div>
						<i class='bx bx-trending-up icon'></i>
					</div>
					<span class="progress" data-value="30%" style="--value: 30%"></span>
					<span class="label">30%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>?</h2>
							<p>Visitors</p>
						</div>
						<i class='bx bx-trending-up icon'></i>
					</div>
					<span class="progress" data-value="80%" style="--value: 80%"></span>
					<span class="label">80%</span>
				</div>
			</div>

			<!-- Data Section -->
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Sales Report</h3>
						<div class="menu">
							<i class='bx bx-dots-horizontal-rounded icon'></i>
							<ul class="menu-link">
								<li><a href="#">Edit</a></li>
								<li><a href="#">Save</a></li>
								<li><a href="#">Remove</a></li>
							</ul>
						</div>
					</div>
					<div class="chart">
						<div id="chart"></div>
					</div>
				</div>
			</div>
			<br>

		<!-- TABLE ADMIN -->
			<div class="wrapper">
    <div class="card">
        <div class="corner-accent"></div>
        <h2><i class="fas fa-list"></i>User Admin</h2>
        <div class="table-admin">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Role</th>
                        <th>Username</th>
                        <th>Durasi Online</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td>
                                <?php if ($row['status'] == 'online' && $row['login_time']) : ?>
                                    <span class="durasi" data-login="<?php echo strtotime($row['login_time']); ?>"></span>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'online') : ?>
                                    <span class="status-active">● Online</span>
                                <?php else : ?>
                                    <span class="status-inactive">● Offline</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
		</main>
		<!-- END MAIN -->
	</section>
	<!-- END CONTENT -->

	<!-- JS -->
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="dasboard_admin.js"></script>
</body>
</html>