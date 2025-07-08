<?php
session_start();
include('../includes/koneksi.php');

if ($_SESSION['role'] != 'wali_kelas') {
    header('Location: ../login.php');
    exit();
}


$kelas_wali = $_SESSION['kelas_wali'];
$nama_lengkap = $_SESSION['nama_lengkap']; // Asumsi ada

// Pagination config
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total siswa di kelas
$sql_count = "SELECT COUNT(*) as total FROM siswa WHERE kelas = '$kelas_wali'";
$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_siswa = $row_count['total'];
$total_pages = ceil($total_siswa / $limit);

// Ambil data siswa dengan limit dan offset
$sql_siswa = "SELECT * FROM siswa WHERE kelas = '$kelas_wali' ORDER BY nama ASC LIMIT $limit OFFSET $offset";
$result_siswa = mysqli_query($conn, $sql_siswa);

// Ambil 12 mapel
$sql_mapel = "SELECT * FROM mapel ORDER BY nama_mapel ASC LIMIT 12";
$result_mapel_master = mysqli_query($conn, $sql_mapel);
$mapel_list = [];
while ($row = mysqli_fetch_assoc($result_mapel_master)) {
    $mapel_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
    <style>
        /* Tambahan styling */
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            font-size: 1.25rem;
        }
        .table thead th {
            vertical-align: middle;
            text-align: center;
        }
        .table tbody td {
            vertical-align: middle;
            text-align: center;
        }
        .pagination {
            justify-content: center;
        }
        .welcome-bar {
            background-color: #343a40;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .welcome-bar .logout-btn {
            color: white;
            text-decoration: none;
            border: 1px solid white;
            padding: 6px 14px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .welcome-bar .logout-btn:hover {
            background-color: white;
            color: #343a40;
        }
    </style>
</head>

<body>
    <div class="welcome-bar">
        <div>Selamat datang, <strong>
            <?php echo htmlspecialchars($nama_lengkap); ?>

    </strong> — Wali Kelas <?php echo htmlspecialchars($kelas_wali); ?></div>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="text-primary">Rekap Nilai Siswa Kelas <?php echo htmlspecialchars($kelas_wali); ?></h3>
            <div>
                <button onclick="window.print()" class="btn btn-danger me-2">Cetak Rekap PDF</button>
                <a href="download_legger.php" class="btn btn-success">Download Legger Excel</a>

                <a href="rekap_print.php" target="_blank" class="btn btn-danger me-2">Download PDF</a>

            </div>
        </div>

        <?php if(mysqli_num_rows($result_siswa) == 0): ?>
            <div class="alert alert-warning">Belum ada data siswa untuk kelas ini.</div>
        <?php else: ?>
            <?php while ($siswa = mysqli_fetch_assoc($result_siswa)) { ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <strong><?php echo htmlspecialchars($siswa['nama']); ?> (NIS: <?php echo htmlspecialchars($siswa['nis']); ?>)</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Mapel</th>
                                    <th>Smt 1</th>
                                    <th>Smt 2</th>
                                    <th>Smt 3</th>
                                    <th>Smt 4</th>
                                    <th>Smt 5</th>
                                    <th>UAS</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $nis = $siswa['nis'];
                            $total_nilai = 0;
                            $kelulusan_akhir = 'Lulus';

                            foreach ($mapel_list as $mapel) {
                                $kode_mapel = $mapel['kode_mapel'];
                                $nama_mapel = $mapel['nama_mapel'];

                                // Ambil nilai untuk siswa dan mapel ini
                                $sql_nilai_per_mapel = "SELECT * FROM nilai WHERE nis = '$nis' AND kode_mapel = '$kode_mapel'";
                                $result_nilai = mysqli_query($conn, $sql_nilai_per_mapel);
                                $nilai = mysqli_fetch_assoc($result_nilai);

                                $smt1 = isset($nilai['smt1']) ? $nilai['smt1'] : '';
                                $smt2 = isset($nilai['smt2']) ? $nilai['smt2'] : '';
                                $smt3 = isset($nilai['smt3']) ? $nilai['smt3'] : '';
                                $smt4 = isset($nilai['smt4']) ? $nilai['smt4'] : '';
                                $smt5 = isset($nilai['smt5']) ? $nilai['smt5'] : '';
                                $uas  = isset($nilai['uas'])  ? $nilai['uas']  : '';

                                echo "<tr>
                                        <td class='text-start'>$nama_mapel</td>
                                        <td>$smt1</td>
                                        <td>$smt2</td>
                                        <td>$smt3</td>
                                        <td>$smt4</td>
                                        <td>$smt5</td>
                                        <td>$uas</td>
                                      </tr>";

                                $nilai_mapel = [$smt1, $smt2, $smt3, $smt4, $smt5, $uas];
                                foreach ($nilai_mapel as $n) {
                                    $nilai_terpakai = ($n === '' || $n === null) ? 0 : $n;
                                    $total_nilai += $nilai_terpakai;
                                    if ($nilai_terpakai < 80) {
                                        $kelulusan_akhir = 'Tidak Lulus';
                                    }
                                }
                            }

                            $rata_rata = $total_nilai / (count($mapel_list) * 6);
                            ?>
                            </tbody>
                        </table>

                        <div class="p-3 bg-light">
                            <p><strong>Jumlah Mata Pelajaran:</strong> <?php echo count($mapel_list); ?></p>
                            <p><strong>Total Nilai Keseluruhan:</strong> <?php echo $total_nilai; ?></p>
                            <p><strong>Rata-rata Nilai (12 mapel × 6):</strong> <?php echo number_format($rata_rata, 2); ?></p>
                            <p><strong>Status Kelulusan:</strong> 
                                <span class="<?php echo $kelulusan_akhir == 'Lulus' ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo $kelulusan_akhir; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <!-- Previous -->
                    <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" tabindex="-1">Sebelumnya</a>
                    </li>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next -->
                    <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">Berikutnya</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <?php include('../template_foot.php'); ?>
</body>
</html>
