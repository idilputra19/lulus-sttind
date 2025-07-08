<?php
session_start();
include('../includes/koneksi.php');

if ($_SESSION['role'] != 'wali_kelas') {
    header('Location: ../login.php');
    exit();
}

$kelas_wali = $_SESSION['kelas_wali'];
$nama_lengkap = $_SESSION['nama_lengkap'];

// Ambil 12 mapel
$sql_mapel = "SELECT * FROM mapel ORDER BY nama_mapel ASC LIMIT 12";
$result_mapel_master = mysqli_query($conn, $sql_mapel);
$mapel_list = [];
while ($row = mysqli_fetch_assoc($result_mapel_master)) {
    $mapel_list[] = $row;
}

// Ambil semua siswa di kelas
$sql_siswa = "SELECT * FROM siswa WHERE kelas = '$kelas_wali' ORDER BY nama ASC";
$result_siswa = mysqli_query($conn, $sql_siswa);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Rekap Nilai Siswa Kelas <?php echo htmlspecialchars($kelas_wali); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            margin: 20px;
            color: #000;
        }
        .kop {
            text-align: center;
            border-bottom: 3px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
            position: relative;
        }
        .kop img.logo {
            position: absolute;
            left: 20px;
            top: 10px;
            width: 70px;
            height: 70px;
        }
        .kop h1 {
            margin: 0;
            font-size: 20pt;
            font-weight: bold;
        }
        .kop p {
            margin: 2px 0;
            font-size: 11pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        .nama-siswa {
            background-color: #cce5ff;
            font-weight: bold;
            text-align: left;
            padding-left: 10px;
        }
        .status-lulus {
            font-weight: bold;
        }
        .lulus {
            color: green;
        }
        .tidak-lulus {
            color: red;
        }
        /* Print button styling */
        .print-btn {
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14pt;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
        /* Hide print button when printing */
        @media print {
            .print-btn {
                display: none;
            }
            body {
                margin: 10mm 15mm;
            }
        }
    </style>
</head>
<body>

<div class="kop">
    <img src="../assets/logo.png" alt="Logo Sekolah" class="logo" />
    <h1>SEKOLAH MENENGAH PERTAMA (SMP) XYZ</h1>
    <p>Jalan Merdeka No. 123, Kota ABC, Provinsi DEF</p>
    <p>Telepon: (021) 1234567 | Email: info@smpxyz.sch.id</p>
</div>

<button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>

<h2>Rekap Nilai Siswa Kelas <?php echo htmlspecialchars($kelas_wali); ?></h2>

<?php if(mysqli_num_rows($result_siswa) == 0): ?>
    <p><em>Belum ada data siswa untuk kelas ini.</em></p>
<?php else: ?>
    <?php while ($siswa = mysqli_fetch_assoc($result_siswa)): ?>
        <table>
            <tr>
                <td colspan="8" class="nama-siswa">
                    <?php echo htmlspecialchars($siswa['nama']); ?> (NIS: <?php echo htmlspecialchars($siswa['nis']); ?>)
                </td>
            </tr>
            <tr>
                <th>Nama Mapel</th>
                <th>Smt 1</th>
                <th>Smt 2</th>
                <th>Smt 3</th>
                <th>Smt 4</th>
                <th>Smt 5</th>
                <th>UAS</th>
            </tr>

            <?php
                $nis = $siswa['nis'];
                $total_nilai = 0;
                $kelulusan_akhir = 'Lulus';

                foreach ($mapel_list as $mapel) {
                    $kode_mapel = $mapel['kode_mapel'];
                    $nama_mapel = $mapel['nama_mapel'];

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
                            <td style='text-align:left;'>$nama_mapel</td>
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

            <tr>
                <td><strong>Jumlah Mapel</strong></td>
                <td colspan="6"><?php echo count($mapel_list); ?></td>
            </tr>
            <tr>
                <td><strong>Total Nilai</strong></td>
                <td colspan="6"><?php echo $total_nilai; ?></td>
            </tr>
            <tr>
                <td><strong>Rata-rata</strong></td>
                <td colspan="6"><?php echo number_format($rata_rata, 2); ?></td>
            </tr>
            <tr>
                <td><strong>Status Kelulusan</strong></td>
                <td colspan="6" class="status-lulus <?php echo ($kelulusan_akhir == 'Lulus' ? 'lulus' : 'tidak-lulus'); ?>">
                    <?php echo $kelulusan_akhir; ?>
                </td>
            </tr>
        </table>
    <?php endwhile; ?>
<?php endif; ?>

</body>
</html>
