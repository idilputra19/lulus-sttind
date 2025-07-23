<?php
session_start();
include('../includes/koneksi.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Ambil tahun ajaran dari tabel settings
$settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings LIMIT 1"));
$tahun_ajaran = $settings['tahun_ajaran'];

// Ambil daftar kelas untuk filter
$kelas_query = mysqli_query($conn, "SELECT DISTINCT kelas FROM siswa WHERE tahun_ajaran = '$tahun_ajaran'");
$kelas_options = [];
while ($row = mysqli_fetch_assoc($kelas_query)) {
    $kelas_options[] = $row['kelas'];
}

// Fitur pencarian dan filter kelas
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$kelas_filter = isset($_GET['kelas']) ? $_GET['kelas'] : '';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query ambil data siswa dan nilai dengan filter kelas
$query = "
    SELECT siswa.nis, siswa.nama, siswa.kelas, nilai.kode_mapel, nilai.smt1, nilai.smt2, nilai.smt3, nilai.smt4, nilai.smt5, nilai.uas, mapel.nama_mapel
    FROM siswa
    LEFT JOIN nilai ON siswa.nis = nilai.nis
    LEFT JOIN mapel ON nilai.kode_mapel = mapel.kode_mapel
    WHERE siswa.tahun_ajaran = '$tahun_ajaran'
    AND (siswa.nis LIKE '%$cari%' OR siswa.nama LIKE '%$cari%' OR mapel.nama_mapel LIKE '%$cari%')
    " . ($kelas_filter ? "AND siswa.kelas = '$kelas_filter'" : "") . "
    ORDER BY siswa.nama ASC
    LIMIT $start, $limit
";

$result = mysqli_query($conn, $query);

// Hitung total data untuk pagination
$total_query = mysqli_query($conn, "
    SELECT COUNT(DISTINCT siswa.nis) as total 
    FROM siswa 
    LEFT JOIN nilai ON siswa.nis = nilai.nis 
    LEFT JOIN mapel ON nilai.kode_mapel = mapel.kode_mapel
    WHERE siswa.tahun_ajaran = '$tahun_ajaran'
    AND (siswa.nis LIKE '%$cari%' OR siswa.nama LIKE '%$cari%' OR mapel.nama_mapel LIKE '%$cari%')
    " . ($kelas_filter ? "AND siswa.kelas = '$kelas_filter'" : "") . "
");
$total = mysqli_fetch_assoc($total_query)['total'];
$pages = ceil($total / $limit);

// Ambil semua data ke array
$data = [];
while ($r = mysqli_fetch_assoc($result)) {
    $data[$r['nis']]['info'] = [
        'nis' => $r['nis'],
        'nama' => $r['nama'],
        'kelas' => $r['kelas']
    ];
    $data[$r['nis']]['mapel'][] = $r;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>View Legger Nilai Siswa | <?= $settings['nama_sekolah'] ?></title>
    <link rel="stylesheet" href="../assets/back/vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/back/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/back/css/main.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .search-form input { width: 80%; }
        .search-form button { width: 15%; }
        .status-lulus { font-weight: bold; }
        .lulus { color: green; }
        .tidak-lulus { color: red; }
        .table-responsive { margin-top: 20px; }
        .pagination a { margin: 0 5px; }
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
            <h2 class="text-center mt-4 mb-4">Legger Nilai Siswa</h2>

            <!-- Filter Kelas dan Pencarian -->
            <form method="GET" action="" class="mb-3 search-form">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="cari" class="form-control" placeholder="Cari siswa atau mapel..." value="<?= htmlspecialchars($cari) ?>">
                    </div>
                    <div class="col-md-4 mb-2">
                        <select name="kelas" class="form-control">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas_options as $kelas): ?>
                                <option value="<?= $kelas ?>" <?= ($kelas_filter == $kelas) ? 'selected' : '' ?>><?= $kelas ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>
            </form>

            <div class="mb-3 text-right">
                <a href="export_excel.php?cari=<?= urlencode($cari) ?>&kelas=<?= urlencode($kelas_filter) ?>" class="btn btn-danger" target="_blank">
                    <i class="fa fa-file-pdf-o"></i> Export PDF
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th rowspan="2">NIS</th>
                        <th rowspan="2">Nama Siswa</th>
                        <th rowspan="2">Kelas</th>
                        <th colspan="7">Mata Pelajaran & Nilai</th>
                        <th rowspan="2">Total Nilai</th>
                        <th rowspan="2">Rata-rata</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
                        <th>Matapelajaran</th>
                        <th>Semester 1</th>
                        <th>Semester 2</th>
                        <th>Semester 3</th>
                        <th>Semester 4</th>
                        <th>Semester 5</th>
                        <th>UAS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $nis => $siswa): 
                            $rowspan = count($siswa['mapel']);
                            $total_nilai = 0;
                            $total_rata = 0; // Total nilai untuk rata-rata
                            $total_mapel = 0; // Total mapel yang dihitung untuk rata-rata
                            foreach ($siswa['mapel'] as $n) {
                                $total_nilai += $n['smt1'] + $n['smt2'] + $n['smt3'] + $n['smt4'] + $n['smt5'] + $n['uas'];
                                $total_rata += ($n['smt1'] + $n['smt2'] + $n['smt3'] + $n['smt4'] + $n['smt5'] + $n['uas']);
                                $total_mapel++;
                            }
                            // Rata-rata dihitung berdasarkan 12 mapel
                            $rata_rata = $total_rata / (12 * 6); // 12 mapel dan 6 nilai per mapel (Semester 1-5 dan UAS)
                            $keterangan = ($total_nilai >= 5400) ? 'Lulus' : 'Tidak Lulus'; // 5400 adalah nilai total minimal untuk 12 mapel
                            $status_class = ($keterangan == 'Lulus') ? 'lulus' : 'tidak-lulus';
                        ?>
                            <?php foreach ($siswa['mapel'] as $i => $n): ?>
                                <tr>
                                    <?php if ($i === 0): ?>
                                        <td rowspan="<?= $rowspan ?>"><?= $siswa['info']['nis'] ?></td>
                                        <td rowspan="<?= $rowspan ?>"><?= $siswa['info']['nama'] ?></td>
                                        <td rowspan="<?= $rowspan ?>"><?= $siswa['info']['kelas'] ?></td>
                                    <?php endif; ?>
                                    <td><?= $n['nama_mapel'] ?></td>
                                    <td><?= $n['smt1'] ?? 'Belum Ditetapkan' ?></td>
                                    <td><?= $n['smt2'] ?? 'Belum Ditetapkan' ?></td>
                                    <td><?= $n['smt3'] ?? 'Belum Ditetapkan' ?></td>
                                    <td><?= $n['smt4'] ?? 'Belum Ditetapkan' ?></td>
                                    <td><?= $n['smt5'] ?? 'Belum Ditetapkan' ?></td>
                                    <td><?= $n['uas'] ?? 'Belum Ditetapkan' ?></td>
                                    <?php if ($i === 0): ?>
                                        <td rowspan="<?= $rowspan ?>"><?= $total_nilai ?></td>
                                        <td rowspan="<?= $rowspan ?>"><?= number_format($rata_rata, 2) ?></td>
                                        <td rowspan="<?= $rowspan ?>" class="status-lulus <?= $status_class ?>"><?= $keterangan ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="12" class="text-center">Data tidak ditemukan</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&cari=<?= urlencode($cari) ?>&kelas=<?= urlencode($kelas_filter) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
</body>
</html>
