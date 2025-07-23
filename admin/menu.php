<head>
    <link rel="icon" href="./img/logo/kop2.png" sizes="32x32" type="image/png">
    <link rel="icon" href="./img/logo/kop2.png" sizes="16x16" type="image/png">
</head>


<?php
session_start(); // Pastikan session dimulai
error_reporting(E_ALL & ~E_NOTICE);

// Debugging: Periksa apakah sesi 'user' ada
if (!isset($_SESSION['user'])) {
    echo "Sesi user tidak ditemukan, pengalihan ke login.php";
} else {
    echo "Sesi user ditemukan: " . $_SESSION['user']['nama_lengkap'];
}

include('../includes/koneksi.php');

// Check if the user session exists
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']; // Fetch user data from session
} else {
    // Redirect to login if user session is not set
    header('Location: ../asd/login.php'); // Corrected 'laogin.php' to 'login.php'
    exit();
}
?>

<!-- Your menu.php content -->
<ul class="nav navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="index.php">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="profile.php"><?= $user['nama_lengkap']; ?></a>
    </li>
    <!-- Add other menu items here -->
</ul>

<style>
    .profile-image img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ddd;
    }

    .profile-info {
        padding-top: 10px;
        color: white;
    }
</style>

<div class="page-sidebar">
    <div class="profile text-center py-4">
        <div class="profile-image">
            <img src="../assets/back/img/admin.png" alt="Admin" />
        </div>
        <div class="profile-info">
            <span class="font-weight-bold"><?= $user['nama_lengkap'] ?></span><br>
            <small class="text-muted"><?= strtoupper($user['role']) ?></small>
        </div>
    </div>

    <ul class="side-menu metismenu">
        <li class="heading">MENU UTAMA</li>

        <li>
            <a href="index.php"><i class="sidebar-item-icon fa fa-home"></i>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="data_siswa.php"><i class="sidebar-item-icon fa fa-users"></i>
                <span class="nav-label">Data Siswa</span>
            </a>
        </li>

        <li>
            <a href="data_mapel.php"><i class="sidebar-item-icon fa fa-users"></i>
                <span class="nav-label">Data Mapel</span>
            </a>
        </li>

        <li>
            <a href="data_user.php"><i class="sidebar-item-icon fa fa-users"></i>
                <span class="nav-label">Data User</span>
            </a>
        </li>

        <li>
            <a href="data_nilai.php"><i class="sidebar-item-icon fa fa-graduation-cap"></i>
                <span class="nav-label">Data Nilai</span>
            </a>
        </li>

         <li>
            <a href="legger.php"><i class="sidebar-item-icon fa fa-graduation-cap"></i>
                <span class="nav-label">legger</span>
            </a>
        </li>
    </ul>

    <ul class="side-menu metismenu">
        <li class="heading">MENU PENGATURAN</li>
        <li>
            <a href="pengaturan.php"><i class="sidebar-item-icon fa fa-cogs"></i>
                <span class="nav-label">Pengaturan</span>
            </a>
        </li>

        <li>
            <a href="logout.php"><i class="sidebar-item-icon fa fa-power-off"></i>
                <span class="nav-label">Keluar</span>
            </a>
        </li>
    </ul>
</div>
