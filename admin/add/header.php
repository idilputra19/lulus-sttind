

<?php
// Aktifkan error reporting saat pengembangan
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include pengaturan jika perlu
// require_once "../../config.php"; // jika Anda punya config global

// Dummy data settings jika belum ada koneksi DB
$settings = [
    'nama_sekolah' => 'SMP Negeri 1 Contoh',
    'logo' => '../../assets/back/img/logo-sekolah.png'
];

$base_url = "../../"; // dari folder /admin/add menuju root
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $settings['nama_sekolah'] ?> | Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $base_url ?>assets/front/images/icons/favicon.ico">

    <!-- Stylesheets -->
    <link href="<?= $base_url ?>assets/back/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>assets/back/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>assets/back/vendors/themify-icons/css/themify-icons.css" rel="stylesheet">
    <link href="<?= $base_url ?>assets/back/css/main.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>assets/back/css/costum.css" rel="stylesheet">
    <link href="<?= $base_url ?>assets/back/vendors/izitoast/css/iziToast.min.css" rel="stylesheet">

    <!-- Style Tambahan -->
    <style>
        .btn { cursor: pointer; }
        .preloader {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 9999; background-color: #fff; opacity: 0.8;
        }
        .preloader .loading {
            position: absolute; left: 50%; top: 50%;
            transform: translate(-50%, -50%); font: 14px arial;
        }
    </style>
</head>


<body class="fixed-navbar fixed-layout">

<!-- PRELOADER -->
<div class="preloader">
    <div class="loading">
        <img src="<?= $base_url ?>assets/back/img/loader.gif" width="80" alt="Loading...">
        <p>Harap Tunggu</p>
    </div>
</div>

<!-- PAGE WRAPPER -->
<div class="page-wrapper">

    <!-- HEADER -->
    <header class="header">
        <div class="page-brand">
            <a class="link" href="<?= $base_url ?>admin/admin_dashboard.php">
                <img src="<?= $settings['logo'] ?>" width="40" alt="LOGO">
                <span class="brand">E-SKL</span>
                <span class="brand-mini">CS</span>
            </a>
        </div>
        <div class="flexbox flex-1">
            <ul class="nav navbar-toolbar">
                <li><a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="ti-menu"></i></a></li>
            </ul>
            <ul class="nav navbar-toolbar">
                <li class="dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                        <img src="<?= $base_url ?>assets/back/img/admin.png" alt="Admin">
                        <span>Admin</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?= $base_url ?>logout.php"><i class="fa fa-power-off"></i> Keluar</a>
                    </ul>
                </li>
            </ul>
        </div>
    </header>

    <!-- SIDEBAR MENU -->
    <aside class="page-sidebar">
        <div class="page-sidebar-inner">
            <ul class="menu">
                <li><a href="<?= $base_url ?>admin/admin_dashboard.php"><i class="ti-dashboard"></i> Dashboard</a></li>
                <li><a href="<?= $base_url ?>admin/data_siswa.php"><i class="ti-user"></i> Data Siswa</a></li>
                <li><a href="<?= $base_url ?>admin/data_mapel.php"><i class="ti-book"></i> Data Mapel</a></li>
                <li><a href="<?= $base_url ?>admin/data_user.php"><i class="ti-id-badge"></i> Data User</a></li>
                <li><a href="<?= $base_url ?>admin/data_nilai.php"><i class="ti-pencil-alt"></i> Data Nilai</a></li>
                <li><a href="<?= $base_url ?>admin/pengaturan.php"><i class="ti-settings"></i> Pengaturan</a></li>
                <li><a href="<?= $base_url ?>logout.php"><i class="ti-power-off"></i> Keluar</a></li>
            </ul>
        </div>
    </aside>

    <!-- MAIN CONTENT (lanjutan di file lain seperti dashboard, dll) -->
<script>
    window.addEventListener("load", function () {
        document.querySelector(".preloader").style.display = "none";
    });
</script>
