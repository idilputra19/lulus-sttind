<?php
session_start();
include('../includes/koneksi.php');

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Ambil tahun ajaran dari tabel settings
$settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings LIMIT 1"));
$tahun_ajaran = $settings['tahun_ajaran'];

// Fitur pencarian
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';

// Setup pagination
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query untuk mengambil data nilai siswa
$query = "
    SELECT siswa.nis, siswa.nama, nilai.kode_mapel, nilai.smt1, nilai.smt2, nilai.smt3, nilai.smt4, nilai.smt5, nilai.uas
    FROM siswa
    LEFT JOIN nilai ON siswa.nis = nilai.nis
    WHERE siswa.tahun_ajaran = '$tahun_ajaran' 
    AND (siswa.nis LIKE '%$cari%' OR siswa.nama LIKE '%$cari%' OR nilai.kode_mapel LIKE '%$cari%')
    ORDER BY siswa.nama ASC
    LIMIT $start, $limit
";

$result = mysqli_query($conn, $query);

// Pagination calculation
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa 
    LEFT JOIN nilai ON siswa.nis = nilai.nis 
    WHERE siswa.tahun_ajaran = '$tahun_ajaran' 
    AND (siswa.nis LIKE '%$cari%' OR siswa.nama LIKE '%$cari%' OR nilai.kode_mapel LIKE '%$cari%')");
$total = mysqli_fetch_assoc($total_query)['total'];
$pages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nilai Siswa | <?= $settings['nama_sekolah'] ?></title>
    <link rel="stylesheet" href="../assets/back/vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/back/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/back/css/main.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .table th, .table td {
            text-align: center;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .search-form input {
            width: 80%;
        }
        .search-form button {
            width: 15%;
        }
        .table tr td {
            vertical-align: middle;
        }
        .table thead th {
            font-weight: bold;
            background-color: #f1f1f1;
        }
        .pagination {
            margin-top: 20px;
        }
    </style>
</head>
<body class="fixed-navbar fixed-layout">
<div class="page-wrapper">
    <header class="header">
        <div class="page-brand">
            <a class="link" href="index.php">
                <img src="<?= $settings['logo'] ?>" width="40" alt="LOGO">
                <span class="brand">E-SKL</span>
            </a>
        </div>
    </header>

    <?php include 'menu.php'; ?>

    <div class="content-wrapper">
        <div class="container-fluid">
            <h2 class="text-center mt-4 mb-4">Daftar Nilai Siswa</h2>

            <form method="GET" action="" class="mb-3 search-form">
                <div class="row">
                    <div class="col-md-8 mb-2">
                        <input type="text" name="cari" class="form-control" placeholder="Cari siswa atau mapel..." value="<?= htmlspecialchars($cari) ?>">
                    </div>
                    <div class="col-md-4 mb-2">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Matapelajaran</th>
                            <th>Semester 1</th>
                            <th>Semester 2</th>
                            <th>Semester 3</th>
                            <th>Semester 4</th>
                            <th>Semester 5</th>
                            <th>UAS</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php
                            $current_nis = '';
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Tampilkan nama siswa hanya sekali untuk setiap NIS
                                if ($current_nis != $row['nis']) {
                                    $current_nis = $row['nis'];
                                    echo "<tr><td colspan='10' class='text-center' style='background-color:#f8f9fa;'><strong>Nama Siswa: " . $row['nama'] . " | NIS: " . $row['nis'] . "</strong></td></tr>";
                                }

                                // Menampilkan nilai siswa per matapelajaran
                                // Jika nilai uas kosong, tampilkan 'Belum Ada'
                                $uas = !empty($row['uas']) ? $row['uas'] : 'Belum Ada';

                                echo "<tr>
                                    <td>" . $row['kode_mapel'] . "</td>
                                    <td>" . $row['smt1'] . "</td>
                                    <td>" . $row['smt2'] . "</td>
                                    <td>" . $row['smt3'] . "</td>
                                    <td>" . $row['smt4'] . "</td>
                                    <td>" . $row['smt5'] . "</td>
                                    <td>" . $uas . "</td> <!-- Menampilkan 'Belum Ada' jika UAS kosong -->
                                    <td>
                                        <button class='btn btn-warning btn-sm' 
                                                data-toggle='modal' 
                                                data-target='#editModal' 
                                                data-nis='" . $row['nis'] . "' 
                                                data-kode_mapel='" . $row['kode_mapel'] . "' 
                                                data-smt1='" . $row['smt1'] . "' 
                                                data-smt2='" . $row['smt2'] . "' 
                                                data-smt3='" . $row['smt3'] . "' 
                                                data-smt4='" . $row['smt4'] . "' 
                                                data-smt5='" . $row['smt5'] . "' 
                                                data-uas='" . $row['uas'] . "'>Edit</button>
                                    </td>
                                </tr>";
                            }
                            ?>
                        <?php else: ?>
                            <tr><td colspan="10" class="text-center">Data tidak ditemukan</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&cari=<?= urlencode($cari) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal for Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Nilai Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="update_nilai.php">
                <div class="modal-body">
                    <input type="hidden" name="nis" id="nis">
                    <input type="hidden" name="kode_mapel" id="kode_mapel">
                    <div class="form-group">
                        <label for="smt1">Semester 1</label>
                        <input type="number" class="form-control" id="smt1" name="smt1" required>
                    </div>
                    <div class="form-group">
                        <label for="smt2">Semester 2</label>
                        <input type="number" class="form-control" id="smt2" name="smt2" required>
                    </div>
                    <div class="form-group">
                        <label for="smt3">Semester 3</label>
                        <input type="number" class="form-control" id="smt3" name="smt3" required>
                    </div>
                    <div class="form-group">
                        <label for="smt4">Semester 4</label>
                        <input type="number" class="form-control" id="smt4" name="smt4" required>
                    </div>
                    <div class="form-group">
                        <label for="smt5">Semester 5</label>
                        <input type="number" class="form-control" id="smt5" name="smt5" required>
                    </div>
                    <div class="form-group">
                        <label for="uas">UAS</label>
                        <input type="number" class="form-control" id="uas" name="uas" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var nis = button.data('nis');
        var kode_mapel = button.data('kode_mapel');
        var smt1 = button.data('smt1');
        var smt2 = button.data('smt2');
        var smt3 = button.data('smt3');
        var smt4 = button.data('smt4');
        var smt5 = button.data('smt5');
        var uas = button.data('uas');

        var modal = $(this);
        modal.find('#nis').val(nis);
        modal.find('#kode_mapel').val(kode_mapel);
        modal.find('#smt1').val(smt1);
        modal.find('#smt2').val(smt2);
        modal.find('#smt3').val(smt3);
        modal.find('#smt4').val(smt4);
        modal.find('#smt5').val(smt5);
        modal.find('#uas').val(uas);
    });
</script>

</body>
</html>
