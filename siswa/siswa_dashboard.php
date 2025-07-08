<?php
session_start();
include('../includes/koneksi.php');

// Cek apakah user sudah login dan memiliki role siswa
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../login.php");
    exit();
}

// Ambil NIS dari session, fallback ke username jika nis belum diset
$nis = $_SESSION['nis'] ?? $_SESSION['username'] ?? null;
if (!$nis) {
    echo "NIS tidak ditemukan di session. Silakan login ulang.";
    exit();
}

// Ambil data siswa dari tabel siswa
$sql_siswa = "SELECT nama FROM siswa WHERE nis = '$nis'";
$result_siswa = mysqli_query($conn, $sql_siswa);
$row_siswa = mysqli_fetch_assoc($result_siswa);
$nama_siswa = $row_siswa['nama'] ?? '-';

// Ambil data nilai dari tabel nilai
$sql_nilai = "SELECT * FROM nilai WHERE nis = '$nis'";
$result_nilai = mysqli_query($conn, $sql_nilai);

// Inisialisasi kelulusan akhir
$total_lulus = true;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<a href="skl.php?nis=<?= $_SESSION['username']; ?>" target="_blank" class="btn btn-success">
    Cetak SKL
</a>

<a href="cetak_skl.php?nis=<?= $_SESSION['username']; ?>" target="_blank" class="btn btn-success">
    Cetak SKL (PDF)
</a>


<div class="container mt-5">
    <h3 class="text-center mb-4">Dashboard Siswa - Status Kelulusan</h3>

    <div class="mb-4">
        <p><strong>Nama:</strong> <?= htmlspecialchars($nama_siswa) ?></p>
        <p><strong>NIS:</strong> <?= htmlspecialchars($nis) ?></p>
    </div>
    <!-- Tombol Cetak SKL (PDF) -->
    <div class="text-center mb-4">
        <a href="cetak_skl.php?nis=<?= $_SESSION['username']; ?>" target="_blank" class="btn btn-success">
            Cetak SKL (PDF)
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>KKM</th>
                    <th>SMT 1</th>
                    <th>SMT 2</th>
                    <th>SMT 3</th>
                    <th>SMT 4</th>
                    <th>SMT 5</th>
                    <th>UAS</th>
                    <th>Rata-rata</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result_nilai)) {
                    // Pastikan kolom mapel tersedia dan aman
                    $mapel = $row['mapel'] ?? '-';

                    $rata_rata = round(
                        ($row['smt1'] + $row['smt2'] + $row['smt3'] + $row['smt4'] + $row['smt5'] + $row['uas']) / 6,
                        2
                    );

                    $lulus_mapel = (
                        $row['smt1'] >= $row['kkm'] &&
                        $row['smt2'] >= $row['kkm'] &&
                        $row['smt3'] >= $row['kkm'] &&
                        $row['smt4'] >= $row['kkm'] &&
                        $row['smt5'] >= $row['kkm'] &&
                        $row['uas'] >= $row['kkm']
                    );

                    if (!$lulus_mapel) {
                        $total_lulus = false;
                    }

                    echo "<tr class='text-center'>
                        <td>{$no}</td>
                        <td>" . htmlspecialchars($mapel) . "</td>
                        <td>{$row['kkm']}</td>
                        <td>{$row['smt1']}</td>
                        <td>{$row['smt2']}</td>
                        <td>{$row['smt3']}</td>
                        <td>{$row['smt4']}</td>
                        <td>{$row['smt5']}</td>
                        <td>{$row['uas']}</td>
                        <td>{$rata_rata}</td>
                        <td class='" . ($lulus_mapel ? "text-success" : "text-danger") . "'>" .
                            ($lulus_mapel ? "Lulus" : "Tidak Lulus") .
                        "</td>
                    </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <h4>Status Kelulusan Akhir:
            <span class="<?= $total_lulus ? 'text-success' : 'text-danger' ?>">
                <?= $total_lulus ? 'Lulus' : 'Tidak Lulus' ?>
            </span>
        </h4>
    </div>
</div>
</body>
</html>
