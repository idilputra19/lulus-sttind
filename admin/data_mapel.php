<?php
session_start();
include('../includes/koneksi.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

$id_user = $_SESSION['id_user'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_user'"));
$_SESSION['user'] = $user;

$settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings LIMIT 1"));
$tahun_ajaran = $settings['tahun_ajaran'];

if (isset($_POST['add_mapel'])) {
    $kode_mapel = $_POST['kode_mapel'];
    $nama_mapel = $_POST['nama_mapel'];

    $sql = "INSERT INTO mapel (kode_mapel, nama_mapel) VALUES ('$kode_mapel', '$nama_mapel')";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Mata pelajaran berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}

$sql = "SELECT * FROM mapel";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mata Pelajaran | <?= $settings['nama_sekolah'] ?></title>
    <link rel="stylesheet" href="../assets/back/vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/back/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/back/css/main.min.css">
    <link rel="stylesheet" href="../assets/back/css/costum.css">
    <style>
        /* CSS yang telah dibahas di atas */
        .content-container {
            padding-left: 250px;
        }

        .sidebar {
            position: fixed;
            z-index: 1000;
            width: 250px;
            height: 100%;
        }

        .table-container {
            position: relative;
            z-index: 1;
        }

        .form-container {
            margin-top: 20px;
        }

        button[type="submit"] {
            width: 100%;
        }
    </style>
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
    <div class="container content-container">
        <h3 class="text-center mb-4 text-gray-800">Kelola Mata Pelajaran</h3>
        <br>
        <br>  
        <h3 class="text-center mb-4 text-gray-800">Kelola Mata Pelajaran</h3>

        <!-- Form Tambah Mata Pelajaran -->
        <div class="form-container">
            <form method="POST" action="data_mapel.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kode_mapel" class="form-label">Kode Mata Pelajaran</label>
                        <input type="text" class="form-control" id="kode_mapel" name="kode_mapel" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_mapel" class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" class="form-control" id="nama_mapel" name="nama_mapel" required>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="add_mapel" class="btn btn-success">Tambah Mata Pelajaran</button>
                </div>
            </form>
        </div>

        <!-- Daftar Mata Pelajaran -->
        <div class="table-container">
            <h4 class="mt-5">Daftar Mata Pelajaran</h4>
            <table class="table table-bordered mt-3">
                
            
            
            <thead>
                    <tr>
                        <th>Kode Mata Pelajaran</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['kode_mapel']; ?></td>
                        <td><?php echo $row['nama_mapel']; ?></td>
                        <td>
                            <!-- Tombol Edit yang memicu modal -->
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal"
                                data-kode_mapel="<?= $row['kode_mapel'] ?>"
                                data-nama_mapel="<?= $row['nama_mapel'] ?>">Edit</button>
                        </td>
                        <td>
                            <a href="delete_mapel.php?kode_mapel=<?php echo $row['kode_mapel']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('../template_foot.php'); ?>

  <!-- Modal Edit Mata Pelajaran -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Mata Pelajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form untuk mengedit mata pelajaran -->
                <form method="POST" action="edit_mapel.php">
                    <div class="form-group">
                        <label for="kode_mapel_edit">Kode Mata Pelajaran</label>
                        <input type="text" class="form-control" id="kode_mapel_edit" name="kode_mapel" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama_mapel_edit">Nama Mata Pelajaran</label>
                        <input type="text" class="form-control" id="nama_mapel_edit" name="nama_mapel" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="edit_mapel" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Script untuk Mengisi Data Modal -->
<script src="../assets/back/vendors/jquery/jquery.min.js"></script>
<script src="../assets/back/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Set data mata pelajaran ke modal
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang mengaktifkan modal
        var kode_mapel = button.data('kode_mapel');
        var nama_mapel = button.data('nama_mapel');

        // Isi data ke dalam form modal
        $('#kode_mapel_edit').val(kode_mapel);
        $('#nama_mapel_edit').val(nama_mapel);
    });
</script>
</body>
</html>