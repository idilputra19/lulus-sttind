<?php
session_start();
include('../includes/koneksi.php');

if ($_SESSION['role'] != 'wali_kelas') {
    header('Location: ../login.php');
    exit();
}

$kelas_wali = $_SESSION['kelas_wali'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=legger_nilai_kelas_$kelas_wali.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Ambil daftar mapel
$sql_mapel = "SELECT * FROM mapel ORDER BY nama_mapel ASC LIMIT 12";
$result_mapel = mysqli_query($conn, $sql_mapel);
$mapel_list = [];
while ($row = mysqli_fetch_assoc($result_mapel)) {
    $mapel_list[] = $row;
}

echo "<table border='1'>";

// Baris 1 header
echo "<tr>
        <th rowspan='2'>Nama Siswa</th>
        <th rowspan='2'>NIS</th>";
foreach ($mapel_list as $mapel) {
    echo "<th colspan='6'>{$mapel['nama_mapel']}</th>";
}
echo "<th rowspan='2'>Total</th>
      <th rowspan='2'>Rata-rata</th>
      <th rowspan='2'>Keterangan</th>
      </tr>";

// Baris 2 header (detail nilai per mapel)
echo "<tr>";
foreach ($mapel_list as $mapel) {
    echo "<th>Smt 1</th>
          <th>Smt 2</th>
          <th>Smt 3</th>
          <th>Smt 4</th>
          <th>Smt 5</th>
          <th>UAS</th>";
}
echo "</tr>";

// Data siswa
$sql_siswa = "SELECT * FROM siswa WHERE kelas = '$kelas_wali' ORDER BY nama ASC";
$result_siswa = mysqli_query($conn, $sql_siswa);

while ($siswa = mysqli_fetch_assoc($result_siswa)) {
    $nis = $siswa['nis'];
    $nama_siswa = $siswa['nama'];

    echo "<tr>
            <td>$nama_siswa</td>
            <td>$nis</td>";

    $total_nilai = 0;
    $count_nilai = 0;

    foreach ($mapel_list as $mapel) {
        $kode_mapel = $mapel['kode_mapel'];

        $sql_nilai = "SELECT * FROM nilai WHERE nis = '$nis' AND kode_mapel = '$kode_mapel'";
        $result_nilai = mysqli_query($conn, $sql_nilai);
        $nilai = mysqli_fetch_assoc($result_nilai);

        $smt1 = isset($nilai['smt1']) ? (float)$nilai['smt1'] : 0;
        $smt2 = isset($nilai['smt2']) ? (float)$nilai['smt2'] : 0;
        $smt3 = isset($nilai['smt3']) ? (float)$nilai['smt3'] : 0;
        $smt4 = isset($nilai['smt4']) ? (float)$nilai['smt4'] : 0;
        $smt5 = isset($nilai['smt5']) ? (float)$nilai['smt5'] : 0;
        $uas  = isset($nilai['uas'])  ? (float)$nilai['uas']  : 0;

        echo "<td>$smt1</td>
              <td>$smt2</td>
              <td>$smt3</td>
              <td>$smt4</td>
              <td>$smt5</td>
              <td>$uas</td>";

        // Hitung total dan jumlah nilai valid
        $total_nilai += $smt1 + $smt2 + $smt3 + $smt4 + $smt5 + $uas;
        $count_nilai += 6; // 6 nilai per mapel
    }

    // Hitung rata-rata
    $rata_rata = $count_nilai > 0 ? round($total_nilai / $count_nilai, 2) : 0;

    // Tentukan keterangan lulus / tidak lulus (KKM = 80)
    $keterangan = ($rata_rata >= 80) ? 'Lulus' : 'Tidak Lulus';

    echo "<td>$total_nilai</td>";
    echo "<td>$rata_rata</td>";
    echo "<td>$keterangan</td>";

    echo "</tr>";
}

echo "</table>";
?>
