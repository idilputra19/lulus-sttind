<?php
session_start();
include('../includes/koneksi.php');

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

$id_user = $_SESSION['id_user'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_user'"));
$settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings LIMIT 1"));

// Tambah pengguna
if (isset($_POST['add_user'])) {
    // Validasi form input
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $role = $_POST['role'];
    $kode_mapel = $_POST['kode_mapel'] ?? null;
    $kelas_wali = $_POST['kelas_wali'] ?? null;

    // Cek jika ada input yang kosong
    if (empty($username) || empty($password) || empty($nama_lengkap) || empty($role)) {
        die("Semua field wajib diisi.");
    }

    // Enkripsi password
    // $password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk memasukkan data pengguna
   $sql = "INSERT INTO users (username, password, nama_lengkap, role, kode_mapel, kelas_wali)
        VALUES ('$username', '$password', '$nama_lengkap', '$role', 
                " . ($role == 'guru_mapel' && $kode_mapel ? "'$kode_mapel'" : "NULL") . ",
                " . ($role == 'wali_kelas' && $kelas_wali ? "'$kelas_wali'" : "NULL") . ")";


    // Cek eksekusi query
    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, redirect ke halaman data_user.php
        header("Location: data_user.php");
        exit();
    } else {
        // Menampilkan pesan error jika query gagal
        die("Error: " . mysqli_error($conn));
    }
}

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna | <?= $settings['nama_sekolah'] ?></title>
    <link rel="stylesheet" href="../assets/back/vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/back/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/back/css/main.min.css">
    <link rel="stylesheet" href="../assets/back/css/costum.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

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

<body class="fixed-navbar fixed-layout">
<div class="page-wrapper">

    <?php include 'menu.php'; ?>

    <div class="content-wrapper">
        <div class="container-fluid">
            <h2 class="text-center mt-4 mb-4">Kelola Pengguna</h2>

            <div class="mb-3 text-end">
                <button class="btn btn-success" data-toggle="modal" data-target="#modalTambahUser">+ Tambah Pengguna</button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Role</th>
                            <th>Kode Mapel</th>
                            <th>Kelas Wali</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['nama_lengkap'] ?></td>
                            <td><?= $row['role'] ?></td>
                            <td><?= $row['kode_mapel'] ?? '-' ?></td>
                            <td><?= $row['kelas_wali'] ?? '-' ?></td>
                            <td>
                                <!-- <button class="btn btn-warning btn-sm btn-edit" data-id="<?= $row['id'] ?>">Edit</button> -->
                                <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pengguna</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input name="username" class="form-control mb-2" placeholder="Username" required>
          <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
          <input name="nama_lengkap" class="form-control mb-2" placeholder="Nama Lengkap" required>
          <select name="role" id="role" class="form-control mb-2" required>
            <option value="">-- Pilih Role --</option>
            <option value="admin">Admin</option>
            <option value="guru_mapel">Guru Mapel</option>
            <option value="wali_kelas">Wali Kelas</option>
            <option value="siswa">Siswa</option>
          </select>
          <input name="kode_mapel" id="kode_mapel" class="form-control mb-2" placeholder="Kode Mapel" style="display:none;">
          <input name="kelas_wali" id="kelas_wali" class="form-control mb-2" placeholder="Kelas Wali" style="display:none;">
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_user" class="btn btn-primary">Simpan</button>
          <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEditUser" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="edit_user.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Pengguna</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <input name="username" id="edit_username" class="form-control mb-2" required>
          <input name="nama_lengkap" id="edit_nama" class="form-control mb-2" required>
          <select name="role" id="edit_role" class="form-control mb-2" required>
            <option value="admin">Admin</option>
            <option value="guru_mapel">Guru Mapel</option>
            <option value="wali_kelas">Wali Kelas</option>
            <option value="siswa">Siswa</option>
          </select>
          <input name="kode_mapel" id="edit_kode_mapel" class="form-control mb-2" placeholder="Kode Mapel" style="display:none;">
          <input name="kelas_wali" id="edit_kelas_wali" class="form-control mb-2" placeholder="Kelas Wali" style="display:none;">
        </div>
        <div class="modal-footer">
          <button type="submit" name="update_user" class="btn btn-primary">Simpan Perubahan</button>
          <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- JS Bootstrap & Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    $('#role').on('change', function () {
        let val = $(this).val();
        $('#kode_mapel').toggle(val === 'guru_mapel');
        $('#kelas_wali').toggle(val === 'wali_kelas');
    });

    $('#edit_role').on('change', function () {
        let val = $(this).val();
        $('#edit_kode_mapel').parent().toggle(val === 'guru_mapel');
        $('#edit_kelas_wali').parent().toggle(val === 'wali_kelas');
    });

    $('.btn-edit').on('click', function () {
        let id = $(this).data('id'); // Ambil data-id dari tombol
        $.get('edit_user.php', { id }, function (res) {
            let data = JSON.parse(res); // Parse JSON response
            $('#edit_id').val(data.id); 
            $('#edit_username').val(data.username); 
            $('#edit_nama').val(data.nama_lengkap);
            $('#edit_role').val(data.role).trigger('change'); // Update role dan trigger event change
            $('#edit_kode_mapel').val(data.kode_mapel); 
            $('#edit_kelas_wali').val(data.kelas_wali);
            $('#modalEditUser').modal('show'); // Menampilkan modal edit
        }).fail(function () {
            alert('Gagal memuat data pengguna.');
        });
    });
});

$(document).ready(function () {
    $('#role').on('change', function () {
        let val = $(this).val();
        // Toggle visibility berdasarkan role
        $('#kode_mapel').toggle(val === 'guru_mapel');
        $('#kelas_wali').toggle(val === 'wali_kelas');
    });

    $('#edit_role').on('change', function () {
        let val = $(this).val();
        // Menyembunyikan atau menampilkan input sesuai dengan role
        $('#edit_kode_mapel').parent().toggle(val === 'guru_mapel');
        $('#edit_kelas_wali').parent().toggle(val === 'wali_kelas');
    });
});

</script>
</body>
</html>
