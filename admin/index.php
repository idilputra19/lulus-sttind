<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);

var_dump($_SESSION);

// Cek sesi login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Koneksi database
$koneksi = new mysqli('localhost', 'root', '', 'db_kelulusan');
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data user & setting
$id_user = $_SESSION['id_user'];
$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id_user'"));
$settings = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM settings LIMIT 1"));
$nama_lengkap = $user['nama_lengkap'];


// Statistik
$count_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa"))['total'];
$count_lulus = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa WHERE status_kelulusan = 'Lulus'"))['total'];
$count_lulus_bersyarat = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa WHERE status_kelulusan = 'Lulus Bersyarat'"))['total'];
$count_tidak_lulus = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa WHERE status_kelulusan = 'Tidak Lulus'"))['total'];
$count_mapel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mapel"))['total'];
$count_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $settings['nama_sekolah'] ?> | Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/front/images/icons/favicon.ico">
    <link href="../assets/back/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/back/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/back/vendors/themify-icons/css/themify-icons.css" rel="stylesheet">
    <link href="../assets/back/css/main.min.css" rel="stylesheet">
    <link href="../assets/back/css/costum.css" rel="stylesheet">
    <link href="../assets/back/vendors/izitoast/css/iziToast.min.css" rel="stylesheet">
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
<div class="preloader">
    <div class="loading">
        <img src="../assets/back/img/loader.gif" width="80">
        <p>Harap Tunggu</p>
    </div>
</div>

<div class="page-wrapper">
    <!-- HEADER -->
    <header class="header">
        <div class="page-brand">
            <a class="link" href="index.php">
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
                        <img src="../assets/back/img/admin.png">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off"></i> Keluar</a>
                    </ul>
                </li>
            </ul>
        </div>
    </header>

    <!-- SIDEBAR -->
    <?php require_once "menu.php"; ?>

    <!-- MAIN CONTENT -->
    <div class="content-wrapper">
        <div class="container-fluid">
            <h2 class="text-center mt-5"> Hallo <b> <?php echo $nama_lengkap; ?> </b>  Selamat Datang di Dashboard Admin </h2>
            <div class="row m-4">
                <div class="col-md-4 mb-4">
                    <div class="card card-body bg-primary text-white shadow rounded">
                        <h4><?= $count_siswa ?> <small>DATA SISWA</small></h4>
                        <a href="siswa.php" class="text-white">More info</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card card-body bg-success text-white shadow rounded">
                        <h4><?= $count_lulus ?> <small>SISWA LULUS</small></h4>
                        <a href="#" class="text-white">More info</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card card-body bg-warning text-white shadow rounded">
                        <h4><?= $count_lulus_bersyarat ?> <small>SISWA LULUS BERSYARAT</small></h4>
                        <a href="#" class="text-white">More info</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card card-body bg-danger text-white shadow rounded">
                        <h4><?= $count_tidak_lulus ?> <small>SISWA TIDAK LULUS</small></h4>
                        <a href="#" class="text-white">More info</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card card-body bg-info text-white shadow rounded">
                        <h4><?= $count_mapel ?> <small>DATA MAPEL</small></h4>
                        <a href="mapel.php" class="text-white">More info</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card card-body bg-dark text-white shadow rounded">
                        <h4><?= $count_user ?> <small>DATA USER</small></h4>
                        <a href="users.php" class="text-white">More info</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="page-footer">
            <div class="font-13">Copyright <?= date('Y') ?> © <b>E-SKL</b></div>
            <a class="px-4" href="https://www.instagram.com/_idilputra19" target="_blank"><b>Made With Love</b></a>
            <div class="to-top"><i class="fa fa-angle-double-up"></i></div>
        </footer>
    </div>
</div>

<!-- JS -->
<script src="../assets/back/vendors/jquery/dist/jquery.min.js"></script>
<script src="../assets/back/vendors/popper.js/dist/umd/popper.min.js"></script>
<script src="../assets/back/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/back/vendors/metisMenu/dist/metisMenu.min.js"></script>
<script src="../assets/back/vendors/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="../assets/back/vendors/chart.js/dist/Chart.min.js"></script>
<script src="../assets/back/vendors/sweetalert/sweetalert.min.js"></script>
<script src="../assets/back/vendors/izitoast/js/iziToast.min.js"></script>
<script src="../assets/back/js/app.min.js"></script>
<script>
    $('.preloader').hide();
</script>
</body>
</html>
