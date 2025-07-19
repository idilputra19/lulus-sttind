<?php
session_start();
include('../includes/koneksi.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

$id_user = $_SESSION['id_user'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_user'"));
$settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings LIMIT 1"));
$tahun_ajaran = $settings['tahun_ajaran'];

// Tambah siswa
if (isset($_POST['add_siswa'])) {
    $nis = $_POST['nis'];
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $nama_ortu = $_POST['nama_orang_tua'];
    $kelas = $_POST['kelas'];
    $tahun = $_POST['tahun_ajaran'];

    $sql = "INSERT INTO siswa (nis, nisn, nama, tempat_lahir, nama_orang_tua, kelas, tahun_ajaran) 
            VALUES ('$nis', '$nisn', '$nama', '$tempat_lahir', '$nama_ortu', '$kelas', '$tahun')";
    mysqli_query($conn, $sql);
    header("Location: data_siswa.php"); // redirect to clear form resubmission
    exit();
}

// Pencarian
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa WHERE 
    nis LIKE '%$cari%' OR nama LIKE '%$cari%' OR kelas LIKE '%$cari%'");
$total = mysqli_fetch_assoc($total_query)['total'];
$pages = ceil($total / $limit);

$result = mysqli_query($conn, "SELECT * FROM siswa 
    WHERE nis LIKE '%$cari%' OR nama LIKE '%$cari%' OR kelas LIKE '%$cari%' 
    ORDER BY nama ASC 
    LIMIT $start, $limit");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Siswa | <?= $settings['nama_sekolah'] ?></title>
    <link rel="stylesheet" href="../assets/back/vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/back/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/back/css/main.min.css">
    <link rel="stylesheet" href="../assets/back/css/costum.css">
</head>
<body class="fixed-navbar fixed-layout">
<div class="page-wrapper">

    <!-- HEADER -->
    <header class="header">
        <div class="page-brand">
            <a class="link" href="index.php">
                <img src="<?= $settings['logo'] ?>" width="40" alt="LOGO">
                <span class="brand">E-SKL</span>
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
    <?php include 'menu.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="content-wrapper">
        <div class="container-fluid">
            <h2 class="text-center mt-4 mb-4">Kelola Data Siswa</h2>

            <!-- Tombol Tambah Siswa -->
            <div class="mb-3 text-end">
                <button class="btn btn-success" data-toggle="modal" data-target="#modalTambahSiswa">+ Tambah Siswa</button>
            </div>

            <!-- Form Cari -->
            <form method="GET" action="" class="mb-3">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="cari" class="form-control" placeholder="Cari siswa..." value="<?= htmlspecialchars($cari) ?>">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <a href="data_siswa.php" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <!-- Tabel Siswa -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Tempat Lahir</th>
                            <th>Nama Orang Tua</th>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= $row['nis']; ?></td>
                                <td><?= $row['nisn']; ?></td>
                                <td><?= $row['nama']; ?></td>
                                <td><?= $row['tempat_lahir']; ?></td>
                                <td><?= $row['nama_orang_tua']; ?></td>
                                <td><?= $row['kelas']; ?></td>
                                <td><?= $row['tahun_ajaran']; ?></td>
                                <td>
                                    <a href="edit_siswa.php?nis=<?= $row['nis']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_siswa.php?nis=<?= $row['nis']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->

            <nav>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&cari=<?= urlencode($cari) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>

        <!-- FOOTER -->
        <footer class="page-footer mt-4">
            <div class="font-13">Copyright <?= date('Y') ?> © <b>E-SKL</b></div>
        </footer>
    </div>
</div>

<!-- MODAL TAMBAH SISWA -->
<div class="modal fade" id="modalTambahSiswa" tabindex="-1" role="dialog" aria-labelledby="modalTambahSiswaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form method="POST" action="">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Siswa Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>NIS</label>
                        <input type="text" name="nis" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>NISN</label>
                        <input type="text" name="nisn" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Nama Siswa</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Nama Orang Tua</label>
                        <input type="text" name="nama_orang_tua" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Kelas</label>
                        <select name="kelas" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <?php
                            $kelas_opt = mysqli_query($conn, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC");
                            while ($k = mysqli_fetch_assoc($kelas_opt)) {
                                echo "<option value='{$k['kelas']}'>{$k['kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" value="<?= $tahun_ajaran ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="add_siswa" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- JS -->
<script src="../assets/back/vendors/jquery/dist/jquery.min.js"></script>
<script src="../assets/back/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/back/js/app.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
