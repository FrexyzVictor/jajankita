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
$query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id, role ASC");
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
			--primary-light: #FF8533;
			--light: #fff;
			--grey: #F5F7FB;
			--dark: #333;
			--shadow: rgba(0, 0, 0, 0.1);
			--shadow-lg: rgba(0, 0, 0, 0.15);
			--orange-light: #FFE5D4;
			--orange-glow: rgba(255, 102, 0, 0.12);
			--accent-yellow: #FFC107;
			--accent-pink: #FF6B9D;
			--accent-blue: #4A90E2;
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

		/* ENHANCED BACKGROUND DECORATIVE PATTERN */
		body::before {
			content: '';
			position: fixed;
			top: 0;
			left: 260px;
			width: calc(100% - 260px);
			height: 100%;
			background-image: 
				radial-gradient(circle at 20% 30%, rgba(255, 102, 0, 0.04) 0%, transparent 50%),
				radial-gradient(circle at 80% 70%, rgba(255, 193, 7, 0.05) 0%, transparent 50%),
				radial-gradient(circle at 50% 50%, rgba(74, 144, 226, 0.03) 0%, transparent 50%);
			pointer-events: none;
			z-index: 0;
		}

		/* FLOATING DECORATION ELEMENTS */
		body::after {
			content: '';
			position: fixed;
			top: 20%;
			right: 10%;
			width: 150px;
			height: 150px;
			background: radial-gradient(circle, rgba(255, 107, 157, 0.06), transparent);
			border-radius: 50%;
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
			width: 280px;
			height: 100%;
			background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
			color: var(--light);
			transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
			box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
			z-index: 100;
			overflow-y: auto;
			overflow-x: hidden;
		}

		#sidebar::-webkit-scrollbar { width: 5px; }
		#sidebar::-webkit-scrollbar-thumb {
			background: rgba(255,255,255,0.3);
			border-radius: 10px;
		}
		#sidebar::-webkit-scrollbar-thumb:hover {
			background: rgba(255,255,255,0.5);
		}

		#sidebar.hide { width: 75px; }

		/* BRAND / LOGO - ENHANCED */
		#sidebar .brand {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 85px;
			padding: 20px;
			background: rgba(255, 255, 255, 0.12);
			backdrop-filter: blur(10px);
			border-bottom: 1px solid rgba(255, 255, 255, 0.15);
			position: relative;
			overflow: hidden;
		}

		#sidebar .brand::before {
			content: '';
			position: absolute;
			width: 100%;
			height: 100%;
			background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
			pointer-events: none;
		}

		#sidebar .brand .logo {
			height: 50px;
			width: auto;
			object-fit: contain;
			transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
			filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
			position: relative;
			z-index: 2;
		}

		#sidebar.hide .brand .logo {
			height: 38px;
		}

		/* SIDE MENU - ENHANCED */
		#sidebar .side-menu { 
			padding: 24px 16px;
		}

		#sidebar .side-menu li.divider {
			margin: 28px 0 14px;
			padding: 0 14px;
			font-size: 10px;
			font-weight: 700;
			letter-spacing: 1.5px;
			opacity: 0.65;
			text-transform: uppercase;
			color: rgba(255, 255, 255, 0.7);
			transition: all 0.3s ease;
		}

		#sidebar.hide .side-menu li.divider {
			text-align: center;
			padding: 0;
			font-size: 8px;
		}

		/* MENU LINKS - ENHANCED WITH BETTER HOVER */
		#sidebar .side-menu > li {
			margin-bottom: 6px;
			position: relative;
		}

		#sidebar .side-menu > li > a {
			display: flex;
			align-items: center;
			color: rgba(255, 255, 255, 0.9);
			padding: 15px 18px;
			border-radius: 12px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			overflow: hidden;
			white-space: nowrap;
		}

		/* HOVER EFFECT WITH GRADIENT */
		#sidebar .side-menu > li > a::before {
			content: '';
			position: absolute;
			left: 0;
			top: 0;
			width: 0;
			height: 100%;
			background: linear-gradient(90deg, rgba(255, 255, 255, 0.25), rgba(255, 255, 255, 0.15));
			transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
			border-radius: 12px;
		}

		#sidebar .side-menu > li > a:hover::before {
			width: 100%;
		}

		#sidebar .side-menu > li > a .icon {
			font-size: 23px;
			min-width: 23px;
			margin-right: 14px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			z-index: 2;
		}

		#sidebar.hide .side-menu > li > a .icon {
			margin-right: 0;
		}

		#sidebar .side-menu > li > a span {
			position: relative;
			z-index: 2;
			font-size: 14.5px;
			font-weight: 500;
		}

		#sidebar .side-menu > li > a .icon-right {
			margin-left: auto;
			margin-right: 0;
			font-size: 18px;
			transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			z-index: 2;
		}

		#sidebar .side-menu > li > a:hover {
			transform: translateX(6px);
			color: #fff;
		}

		#sidebar .side-menu > li > a:hover .icon {
			transform: scale(1.1) rotate(5deg);
		}

		#sidebar .side-menu > li > a.active {
			background: var(--light);
			color: var(--primary);
			font-weight: 600;
			box-shadow: 0 6px 20px rgba(0,0,0,0.15), 0 0 0 1px rgba(255, 255, 255, 0.1);
			transform: translateX(4px);
		}

		#sidebar .side-menu > li > a.active::before {
			display: none;
		}

		#sidebar .side-menu > li > a.active .icon {
			transform: scale(1.05);
		}

		/* DROPDOWN MENU - FIXED & ENHANCED */
		#sidebar .side-menu .side-dropdown {
			padding-left: 54px;
			max-height: 0;
			overflow: hidden;
			transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), 
			            padding 0.5s cubic-bezier(0.4, 0, 0.2, 1), 
			            opacity 0.3s ease;
			opacity: 0;
		}

		#sidebar .side-menu > li.active .side-dropdown {
			max-height: 600px;
			padding-top: 10px;
			padding-bottom: 10px;
			opacity: 1;
		}

		#sidebar .side-menu > li.active > a .icon-right {
			transform: rotate(90deg);
		}

		#sidebar .side-menu .side-dropdown li {
			margin-bottom: 4px;
		}

		#sidebar .side-menu .side-dropdown li a {
			display: block;
			padding: 11px 18px;
			color: rgba(255, 255, 255, 0.8);
			border-radius: 10px;
			font-size: 13.5px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			overflow: hidden;
		}

		#sidebar .side-menu .side-dropdown li a::before {
			content: '';
			position: absolute;
			left: 0;
			top: 50%;
			transform: translateY(-50%);
			width: 3px;
			height: 0;
			background: var(--light);
			transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			border-radius: 0 3px 3px 0;
		}

		#sidebar .side-menu .side-dropdown li a:hover::before {
			height: 70%;
		}

		#sidebar .side-menu .side-dropdown li a:hover {
			background: rgba(255, 255, 255, 0.18);
			color: #fff;
			padding-left: 24px;
			transform: translateX(2px);
		}

		/* HIDE TEXT WHEN SIDEBAR COLLAPSED */
		#sidebar.hide .side-menu > li > a span,
		#sidebar.hide .side-menu > li > a .icon-right,
		#sidebar.hide .side-menu .side-dropdown {
			opacity: 0;
			visibility: hidden;
			width: 0;
		}

		/* ===== CONTENT ===== */
		#content {
			position: relative;
			left: 280px;
			width: calc(100% - 280px);
			transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
			min-height: 100vh;
		}

		#sidebar.hide + #content {
			left: 75px;
			width: calc(100% - 75px);
		}

		/* ===== NAVBAR - ENHANCED ===== */
		nav {
			height: 75px;
			background: var(--light);
			padding: 0 32px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			box-shadow: 0 3px 16px rgba(0,0,0,0.08);
			position: sticky;
			top: 0;
			z-index: 50;
		}

		/* LEFT SIDE NAVBAR */
		nav .nav-left {
			display: flex;
			align-items: center;
			gap: 24px;
			flex: 1;
		}

		nav .toggle-sidebar {
			font-size: 26px;
			color: var(--primary);
			cursor: pointer;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			padding: 10px;
			border-radius: 10px;
			background: transparent;
		}

		nav .toggle-sidebar:hover {
			background: linear-gradient(135deg, var(--orange-glow), rgba(255, 133, 51, 0.15));
			transform: rotate(90deg) scale(1.1);
		}

		nav .form-group {
			position: relative;
			flex: 1;
			max-width: 500px;
		}

		nav .form-group input {
			width: 100%;
			border: 2px solid transparent;
			background: var(--grey);
			padding: 14px 50px 14px 24px;
			border-radius: 30px;
			outline: none;
			font-size: 14px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		}

		nav .form-group input:focus {
			border-color: var(--primary);
			box-shadow: 0 0 0 4px rgba(255, 102, 0, 0.08);
			background: #fff;
		}

		nav .form-group input::placeholder {
			color: #999;
		}

		nav .form-group .icon {
			position: absolute;
			right: 22px;
			top: 50%;
			transform: translateY(-50%);
			color: var(--primary);
			font-size: 20px;
			pointer-events: none;
		}

		/* RIGHT SIDE NAVBAR - REPOSITIONED */
		nav .nav-right {
			display: flex;
			align-items: center;
			gap: 12px;
		}

		nav .nav-link {
			position: relative;
			color: var(--dark);
			font-size: 24px;
			padding: 12px;
			border-radius: 12px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			cursor: pointer;
			background: transparent;
		}

		nav .nav-link:hover {
			background: linear-gradient(135deg, var(--orange-glow), rgba(255, 133, 51, 0.12));
			color: var(--primary);
			transform: translateY(-2px);
		}

		nav .nav-link .badge {
			position: absolute;
			top: 8px;
			right: 8px;
			background: linear-gradient(135deg, var(--primary), var(--primary-dark));
			color: white;
			font-size: 10px;
			padding: 4px 7px;
			border-radius: 12px;
			font-weight: 700;
			min-width: 20px;
			text-align: center;
			box-shadow: 0 2px 8px rgba(255, 102, 0, 0.4);
			animation: pulse 2s infinite;
		}

		@keyframes pulse {
			0%, 100% { transform: scale(1); }
			50% { transform: scale(1.1); }
		}

		nav .divider {
			width: 2px;
			height: 32px;
			background: linear-gradient(to bottom, transparent, #ddd, transparent);
			margin: 0 8px;
		}

		/* PROFILE DROPDOWN - ENHANCED */
		nav .profile {
			position: relative;
		}

		nav .profile-toggle {
			display: flex;
			align-items: center;
			gap: 12px;
			padding: 8px 16px 8px 8px;
			border-radius: 30px;
			cursor: pointer;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			background: transparent;
			border: 2px solid transparent;
		}

		nav .profile-toggle:hover {
			background: var(--grey);
			border-color: var(--orange-glow);
			transform: translateY(-2px);
		}

		nav .profile-toggle img {
			width: 45px;
			height: 45px;
			border-radius: 50%;
			object-fit: cover;
			border: 3px solid var(--primary);
			box-shadow: 0 4px 12px rgba(255, 102, 0, 0.3);
			transition: all 0.3s ease;
		}

		nav .profile-toggle:hover img {
			transform: scale(1.05);
			border-color: var(--primary-dark);
		}

		nav .profile-toggle .profile-info {
			display: flex;
			flex-direction: column;
			align-items: flex-start;
		}

		nav .profile-toggle .profile-name {
			font-size: 14px;
			font-weight: 600;
			color: var(--dark);
		}

		nav .profile-toggle .profile-role {
			font-size: 11.5px;
			color: #666;
			font-weight: 500;
		}

		nav .profile-toggle i {
			font-size: 18px;
			color: #666;
			transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		}

		nav .profile.active .profile-toggle i {
			transform: rotate(180deg);
		}

		/* PROFILE DROPDOWN MENU - FIXED */
		nav .profile-link {
			position: absolute;
			top: calc(100% + 16px);
			right: 0;
			background: white;
			box-shadow: 0 12px 32px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.05);
			border-radius: 16px;
			padding: 14px;
			min-width: 240px;
			opacity: 0;
			visibility: hidden;
			transform: translateY(-12px);
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			z-index: 100;
		}

		nav .profile.active .profile-link {
			opacity: 1;
			visibility: visible;
			transform: translateY(0);
		}

		nav .profile-link::before {
			content: '';
			position: absolute;
			top: -8px;
			right: 24px;
			width: 16px;
			height: 16px;
			background: white;
			transform: rotate(45deg);
			box-shadow: -2px -2px 4px rgba(0,0,0,0.05);
		}

		nav .profile-link li a {
			display: flex;
			align-items: center;
			gap: 14px;
			padding: 14px 16px;
			color: var(--dark);
			border-radius: 10px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			font-size: 14px;
			font-weight: 500;
			position: relative;
			overflow: hidden;
		}

		nav .profile-link li a::before {
			content: '';
			position: absolute;
			left: 0;
			top: 0;
			width: 0;
			height: 100%;
			background: linear-gradient(90deg, var(--orange-glow), rgba(255, 133, 51, 0.1));
			transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			border-radius: 10px;
		}

		nav .profile-link li a:hover::before {
			width: 100%;
		}

		nav .profile-link li a i {
			font-size: 20px;
			color: var(--primary);
			position: relative;
			z-index: 2;
		}

		nav .profile-link li a:hover {
			padding-left: 22px;
			color: var(--primary);
		}

		nav .profile-link li a span {
			position: relative;
			z-index: 2;
		}

		/* ===== MAIN ===== */
		main {
			padding: 36px;
			position: relative;
			z-index: 1;
		}

		/* ENHANCED GEOMETRIC DECORATIONS IN MAIN */
		main::before {
			content: '';
			position: absolute;
			top: 120px;
			right: 60px;
			width: 100px;
			height: 100px;
			background: linear-gradient(135deg, rgba(255, 193, 7, 0.12), rgba(255, 102, 0, 0.1));
			transform: rotate(45deg);
			border-radius: 14px;
			pointer-events: none;
			z-index: -1;
			animation: float 6s ease-in-out infinite;
		}

		main::after {
			content: '';
			position: absolute;
			bottom: 180px;
			left: 100px;
			width: 75px;
			height: 75px;
			background: linear-gradient(135deg, rgba(255, 107, 157, 0.1), rgba(255, 102, 0, 0.08));
			border-radius: 50%;
			pointer-events: none;
			z-index: -1;
			animation: float 8s ease-in-out infinite reverse;
		}

		@keyframes float {
			0%, 100% { transform: translateY(0) rotate(45deg); }
			50% { transform: translateY(-20px) rotate(45deg); }
		}

		/* ===== WELCOME BOX WITH ENHANCED DECORATIONS ===== */
		.welcome-box {
			position: relative;
			background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
			color: white;
			padding: 45px;
			border-radius: 24px;
			margin-bottom: 36px;
			overflow: hidden;
			box-shadow: 0 16px 40px rgba(255, 102, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
		}

		/* Enhanced Decorative Blobs */
		.welcome-box::before {
			content: '';
			position: absolute;
			width: 220px;
			height: 220px;
			background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
			border-radius: 50%;
			top: -90px;
			right: -60px;
			animation: float 10s ease-in-out infinite;
		}

		.welcome-box::after {
			content: '';
			position: absolute;
			width: 170px;
			height: 170px;
			background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
			border-radius: 50%;
			bottom: -70px;
			left: 60px;
			animation: float 12s ease-in-out infinite reverse;
		}

		/* Additional decorative elements */
		.welcome-box .deco-circle {
			position: absolute;
			border-radius: 50%;
			background: radial-gradient(circle, var(--orange-light) 0%, transparent 70%);
			opacity: 0.25;
			pointer-events: none;
		}

		.welcome-box .deco-circle:nth-child(1) {
			width: 140px;
			height: 140px;
			top: 25px;
			right: 120px;
			animation: float 8s ease-in-out infinite;
		}

		.welcome-box .deco-circle:nth-child(2) {
			width: 95px;
			height: 95px;
			bottom: 50px;
			right: 220px;
			animation: float 10s ease-in-out infinite reverse;
		}

		.welcome-box .deco-circle:nth-child(3) {
			width: 70px;
			height: 70px;
			top: 50%;
			left: 15%;
			animation: float 9s ease-in-out infinite;
		}

		/* Dotted pattern decoration */
		.welcome-box .deco-dots {
			position: absolute;
			top: 35px;
			left: 45px;
			width: 120px;
			height: 120px;
			background-image: radial-gradient(circle, rgba(255, 255, 255, 0.2) 2px, transparent 2px);
			background-size: 16px 16px;
			opacity: 0.5;
			pointer-events: none;
			z-index: 1;
		}

		/* Wave decoration */
		.welcome-box .deco-wave {
			position: absolute;
			bottom: 0;
			right: 0;
			width: 220px;
			height: 70px;
			background: linear-gradient(135deg, rgba(255, 255, 255, 0.08), transparent);
			border-radius: 50% 50% 0 0;
			pointer-events: none;
			z-index: 1;
		}

		.welcome-box h2 {
			position: relative;
			z-index: 2;
			font-size: 30px;
			margin-bottom: 12px;
			text-shadow: 0 2px 8px rgba(0,0,0,0.15);
		}

		.welcome-box p {
			position: relative;
			z-index: 2;
			font-size: 16.5px;
			opacity: 0.95;
		}

		/* ===== BREADCRUMBS ===== */
		.title {
			font-size: 30px;
			font-weight: 700;
			color: var(--dark);
			margin-bottom: 12px;
		}

		.breadcrumbs {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 36px;
			font-size: 14px;
		}

		.breadcrumbs li a {
			color: var(--primary);
			transition: all 0.3s ease;
			font-weight: 500;
		}

		.breadcrumbs li a:hover {
			color: var(--primary-dark);
			text-decoration: underline;
		}

		.breadcrumbs .divider {
			color: #999;
		}

		/* ===== INFO CARDS WITH ENHANCED DECORATIONS ===== */
		.info-data {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
			gap: 24px;
			margin-bottom: 36px;
		}

		.info-data .card {
			position: relative;
			background: var(--light);
			padding: 28px;
			border-radius: 18px;
			box-shadow: 0 8px 24px var(--shadow);
			transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
			overflow: hidden;
			border: 1px solid rgba(255, 102, 0, 0.08);
		}

		/* Enhanced decorations */
		.info-data .card::before {
			content: '';
			position: absolute;
			width: 120px;
			height: 120px;
			background: radial-gradient(circle, var(--orange-glow) 0%, transparent 70%);
			border-radius: 50%;
			top: -35px;
			right: -35px;
			transition: all 0.4s ease;
		}

		.info-data .card::after {
			content: '';
			position: absolute;
			width: 60px;
			height: 60px;
			background: linear-gradient(135deg, rgba(255, 193, 7, 0.12), transparent);
			bottom: 0;
			left: 0;
			border-radius: 0 18px 0 0;
		}

		.info-data .card:hover {
			transform: translateY(-10px);
			box-shadow: 0 16px 40px var(--shadow-lg);
			border-color: var(--primary);
		}

		.info-data .card:hover::before {
			transform: scale(1.2);
			opacity: 0.8;
		}

		.info-data .card .head {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			margin-bottom: 22px;
			position: relative;
			z-index: 2;
		}

		.info-data .card .head h2 {
			font-size: 34px;
			font-weight: 700;
			color: var(--primary);
			transition: all 0.3s ease;
		}

		.info-data .card:hover .head h2 {
			transform: scale(1.05);
		}

		.info-data .card .head p {
			font-size: 14px;
			color: #666;
			margin-top: 6px;
			font-weight: 500;
		}

		.info-data .card .head .icon {
			font-size: 38px;
			color: var(--primary);
			opacity: 0.3;
			transition: all 0.4s ease;
		}

		.info-data .card:hover .head .icon {
			opacity: 0.5;
			transform: rotate(15deg) scale(1.1);
		}

		.info-data .card .head .icon.down {
			color: #dc3545;
		}

		.info-data .card .progress {
			display: block;
			height: 10px;
			background: var(--grey);
			border-radius: 12px;
			overflow: hidden;
			position: relative;
			z-index: 2;
			box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
		}

		.info-data .card .progress::before {
			content: '';
			position: absolute;
			left: 0;
			top: 0;
			height: 100%;
			width: var(--value);
			background: linear-gradient(90deg, var(--primary), var(--primary-light));
			border-radius: 12px;
			transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
		}

		.info-data .card:hover .progress::before {
			box-shadow: 0 0 12px var(--primary);
		}

		.info-data .card .label {
			display: block;
			margin-top: 10px;
			font-size: 13px;
			color: var(--primary);
			font-weight: 700;
			position: relative;
			z-index: 2;
		}

		/* ===== DATA SECTION ===== */
		.data {
			margin-bottom: 36px;
		}

		.content-data {
			position: relative;
			background: var(--light);
			padding: 30px;
			border-radius: 18px;
			box-shadow: 0 8px 24px var(--shadow);
			overflow: hidden;
			border: 1px solid rgba(255, 102, 0, 0.08);
		}

		/* Enhanced decorations */
		.content-data::after {
			content: '';
			position: absolute;
			width: 170px;
			height: 170px;
			background: radial-gradient(circle, var(--orange-glow) 0%, transparent 70%);
			border-radius: 50%;
			top: -60px;
			right: 60px;
		}

		.content-data::before {
			content: '';
			position: absolute;
			bottom: 25px;
			left: 25px;
			width: 120px;
			height: 4px;
			background: linear-gradient(90deg, var(--primary), transparent);
			border-radius: 12px;
			pointer-events: none;
		}

		.content-data .head {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 24px;
			position: relative;
			z-index: 2;
		}

		.content-data .head h3 {
			font-size: 22px;
			font-weight: 700;
			color: var(--dark);
		}

		.content-data .menu {
			position: relative;
			cursor: pointer;
		}

		.content-data .menu .icon {
			font-size: 26px;
			color: var(--dark);
			padding: 8px;
			border-radius: 10px;
			transition: all 0.3s ease;
		}

		.content-data .menu .icon:hover {
			background: var(--grey);
			color: var(--primary);
			transform: rotate(90deg);
		}

		.content-data .menu-link {
			position: absolute;
			top: calc(100% + 12px);
			right: 0;
			background: white;
			box-shadow: 0 8px 24px var(--shadow);
			border-radius: 12px;
			padding: 10px;
			min-width: 140px;
			opacity: 0;
			pointer-events: none;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			z-index: 10;
		}

		.content-data .menu:hover .menu-link {
			opacity: 1;
			pointer-events: all;
		}

		.content-data .menu-link li a {
			display: block;
			padding: 10px 14px;
			color: var(--dark);
			border-radius: 8px;
			transition: all 0.3s ease;
			font-size: 14px;
			font-weight: 500;
		}

		.content-data .menu-link li a:hover {
			background: var(--orange-glow);
			color: var(--primary);
			padding-left: 18px;
		}

		.content-data .chart {
			position: relative;
			z-index: 2;
		}

		/* ===== TABLE WRAPPER WITH ENHANCED DECORATIONS ===== */
		.wrapper {
			position: relative;
		}

		.wrapper .card {
			position: relative;
			background: var(--light);
			padding: 34px;
			border-radius: 18px;
			box-shadow: 0 8px 24px var(--shadow);
			overflow: hidden;
			border: 1px solid rgba(255, 102, 0, 0.08);
		}

		/* Enhanced decorations */
		.wrapper .card::before {
			content: '';
			position: absolute;
			width: 140px;
			height: 140px;
			background: radial-gradient(circle, var(--orange-glow) 0%, transparent 70%);
			border-radius: 50%;
			bottom: -50px;
			right: 120px;
		}

		.wrapper .card::after {
			content: '';
			position: absolute;
			width: 85px;
			height: 85px;
			background: radial-gradient(circle, rgba(255, 193, 7, 0.1), transparent);
			top: 50px;
			left: 35px;
			border-radius: 50%;
			pointer-events: none;
		}

		.wrapper .card .corner-accent {
			position: absolute;
			top: 0;
			right: 0;
			width: 120px;
			height: 120px;
			background: linear-gradient(135deg, transparent 50%, rgba(255, 102, 0, 0.06) 50%);
			pointer-events: none;
			border-radius: 0 18px 0 0;
		}

		.wrapper .card h2 {
			position: relative;
			z-index: 2;
			font-size: 24px;
			margin-bottom: 24px;
			color: var(--dark);
			display: flex;
			align-items: center;
			gap: 12px;
			font-weight: 700;
		}

		.wrapper .card h2 i {
			color: var(--primary);
			font-size: 26px;
		}

		/* ===== TABLE ===== */
		.table-admin {
			position: relative;
			z-index: 2;
			overflow-x: auto;
		}

		.table-admin::-webkit-scrollbar {
			height: 8px;
		}

		.table-admin::-webkit-scrollbar-thumb {
			background: var(--primary);
			border-radius: 10px;
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
			padding: 18px;
			text-align: left;
			font-weight: 700;
			font-size: 13px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.table-admin td {
			padding: 18px;
			border-bottom: 1px solid #eee;
			font-size: 14px;
		}

		.table-admin tbody tr {
			transition: all 0.3s ease;
		}

		.table-admin tbody tr:hover {
			background: linear-gradient(90deg, #fff8f3, #fff);
			transform: translateX(4px);
		}

		/* Enhanced Status badges */
		.status-active {
			background: linear-gradient(135deg, #28a745, #20c997);
			color: #fff;
			padding: 7px 14px;
			border-radius: 14px;
			font-size: 12px;
			font-weight: 700;
			display: inline-block;
			box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.status-inactive {
			background: linear-gradient(135deg, #dc3545, #c82333);
			color: #fff;
			padding: 7px 14px;
			border-radius: 14px;
			font-size: 12px;
			font-weight: 700;
			display: inline-block;
			box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		/* ===== RESPONSIVE ===== */
		@media (max-width: 992px) {
			#sidebar { width: 240px; }
			#content {
				left: 240px;
				width: calc(100% - 240px);
			}
			body::before {
				left: 240px;
				width: calc(100% - 240px);
			}
		}

		@media (max-width: 768px) {
			#sidebar {
				left: -280px;
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
			main { padding: 24px; }
			.info-data {
				grid-template-columns: 1fr;
			}
			nav .form-group {
				max-width: 200px;
			}
			nav .profile-toggle .profile-info {
				display: none;
			}
		}

		@media (max-width: 576px) {
			.welcome-box {
				padding: 28px;
			}
			.welcome-box h2 {
				font-size: 24px;
			}
			main .title {
				font-size: 24px;
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
			<li><a href="#" class="active"><i class='bx bxs-dashboard icon'></i><span>Dashboard</span></a></li>
			<li class="divider">SHOP</li>
			<li class="has-dropdown">
				<a href="#" onclick="toggleDropdown(this); return false;">
					<i class='bx bxs-inbox icon'></i>
					<span>Pengelolaan Produk</span>
					<i class='bx bx-chevron-right icon-right'></i>
				</a>
				<ul class="side-dropdown">
					<li><a href="pesanan.php">Manage Produk</a></li>
					<li><a href="#">Manage Pesanan</a></li>
					<li><a href="#">Manage Pengguna</a></li>
					<li><a href="#">Transaksi & Pembayaran</a></li>
					<li><a href="#">Pengiriman</a></li>
					<li><a href="#">Kategori & Subkategori Produk</a></li>
				</ul>
			</li>
			<li><a href="#"><i class='bx bxs-chart icon'></i><span>Charts</span></a></li>
			<li><a href="#"><i class='bx bxs-widget icon'></i><span>Widgets</span></a></li>
			<li class="divider">TABLE AND FORMS</li>
			<li><a href="#"><i class='bx bx-table icon'></i><span>Tables</span></a></li>
			<li class="has-dropdown">
				<a href="#" onclick="toggleDropdown(this); return false;">
					<i class='bx bxs-notepad icon'></i>
					<span>Forms</span>
					<i class='bx bx-chevron-right icon-right'></i>
				</a>
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
			<div class="nav-left">
				<i class='bx bx-menu toggle-sidebar' onclick="toggleSidebar()"></i>
				<form action="#">
					<div class="form-group">
						<input type="text" placeholder="Search...">
						<i class='bx bx-search icon'></i>
					</div>
				</form>
			</div>
			<div class="nav-right">
				<a href="#" class="nav-link">
					<i class='bx bxs-bell'></i>
					<span class="badge">5</span>
				</a>
				<a href="#" class="nav-link">
					<i class='bx bxs-message-square-dots'></i>
					<span class="badge">8</span>
				</a>
				<span class="divider"></span>
				<div class="profile" onclick="toggleProfile()">
					<div class="profile-toggle">
						<img src="uploads/lil bahlil.jpg" alt="Profile">
						<div class="profile-info">
							<span class="profile-name"><?php echo $namaAdmin; ?></span>
							<span class="profile-role">Administrator</span>
						</div>
						<i class='bx bx-chevron-down'></i>
					</div>
					<ul class="profile-link">
						<li><a href="#"><i class='bx bxs-user-circle'></i><span>Profile</span></a></li>
						<li><a href="#"><i class='bx bxs-cog'></i><span>Settings</span></a></li>
						<li><a href="logout.php"><i class='bx bxs-log-out-circle'></i><span>Logout</span></a></li>
					</ul>
				</div>
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
				<h2>Selamat Datang di Website Admin Dashboard 🎉</h2>
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
							<h2>1,234</h2>
							<p>Traffic</p>
						</div>
						<i class='bx bx-trending-up icon'></i>
					</div>
					<span class="progress" style="--value: 40%"></span>
					<span class="label">40%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>5,678</h2>
							<p>Sales</p>
						</div>
						<i class='bx bx-trending-down icon down'></i>
					</div>
					<span class="progress" style="--value: 60%"></span>
					<span class="label">60%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>9,012</h2>
							<p>Pageviews</p>
						</div>
						<i class='bx bx-trending-up icon'></i>
					</div>
					<span class="progress" style="--value: 30%"></span>
					<span class="label">30%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>3,456</h2>
							<p>Visitors</p>
						</div>
						<i class='bx bx-trending-up icon'></i>
					</div>
					<span class="progress" style="--value: 80%"></span>
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

			<!-- TABLE ADMIN -->
			<div class="wrapper">
				<div class="card">
					<div class="corner-accent"></div>
					<h2><i class='bx bxs-user-detail'></i>Manage User</h2>
					<div class="table-admin">
						<table>
							<thead>
								<tr>
									<th>ID</th>
									<th>Role</th>
									<th>Username</th>
									<th>Duration Time</th>
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
	<script>
		// Toggle Sidebar
		function toggleSidebar() {
			document.getElementById('sidebar').classList.toggle('hide');
		}

		// Toggle Dropdown in Sidebar
		function toggleDropdown(element) {
			const parent = element.parentElement;
			const allDropdowns = document.querySelectorAll('.side-menu > li.has-dropdown');
			
			// Close other dropdowns
			allDropdowns.forEach(item => {
				if (item !== parent) {
					item.classList.remove('active');
				}
			});
			
			// Toggle current dropdown
			parent.classList.toggle('active');
		}

		// Toggle Profile Dropdown
		function toggleProfile() {
			const profile = document.querySelector('nav .profile');
			profile.classList.toggle('active');
		}

		// Close profile dropdown when clicking outside
		document.addEventListener('click', function(event) {
			const profile = document.querySelector('nav .profile');
			if (!profile.contains(event.target)) {
				profile.classList.remove('active');
			}
		});

		// Prevent profile dropdown from closing when clicking inside
		document.querySelector('nav .profile').addEventListener('click', function(event) {
			event.stopPropagation();
		});

		// Update duration time
		function updateDurations() {
			const durations = document.querySelectorAll('.durasi');
			durations.forEach(el => {
				const loginTime = parseInt(el.getAttribute('data-login'));
				const now = Math.floor(Date.now() / 1000);
				const diff = now - loginTime;
				
				const hours = Math.floor(diff / 3600);
				const minutes = Math.floor((diff % 3600) / 60);
				const seconds = diff % 60;
				
				el.textContent = `${hours}h ${minutes}m ${seconds}s`;
			});
		}

		// Update durations every second
		setInterval(updateDurations, 1000);
		updateDurations();
	</script>
</body>
</html>