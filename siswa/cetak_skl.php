<?php
session_start();
include('../includes/koneksi.php');

if ($_SESSION['role'] != 'siswa') {
    header('Location: ../login.php');
    exit();
}

$nis = $_SESSION['username'];

// Ambil data siswa
$sql_siswa = "SELECT * FROM siswa WHERE nis = '$nis'";
$result_siswa = mysqli_query($conn, $sql_siswa);
if (!$result_siswa) {
    die("Query siswa gagal: " . mysqli_error($conn));
}
$siswa = mysqli_fetch_assoc($result_siswa);

// Ambil data nilai
$sql_nilai = "SELECT * FROM nilai WHERE nis = '$nis'";
$result_nilai = mysqli_query($conn, $sql_nilai);
if (!$result_nilai) {
    die("Query nilai gagal: " . mysqli_error($conn));
}

// Mapping kode_mapel ke nama mapel
$mapel_nama = [
    'BIN01' => 'Bahasa Indonesia',
    'IPA01' => 'Ilmu Pengetahuan Alam',
    'IPS01' => 'Ilmu Pengetahuan Sosial',
    'AGM01' => 'Pendidikan Agama',
    'ING01' => 'Bahasa Inggris',
    'SEN01' => 'Seni Budaya',
    // Tambahkan lainnya sesuai kebutuhan
];

// Cek kelulusan keseluruhan
$nilai_array = [];
$status = "Lulus";
while ($n = mysqli_fetch_assoc($result_nilai)) {
    // Menghitung nilai rata-rata dari semester 1-5 dan UAS
    $rata_rata = ($n['smt1'] + $n['smt2'] + $n['smt3'] + $n['smt4'] + $n['smt5'] + $n['uas']) / 6;

    // Menentukan status kelulusan berdasarkan KKM dan nilai rata-rata
    if (
        $n['smt1'] < $n['kkm'] || $n['smt2'] < $n['kkm'] || $n['smt3'] < $n['kkm'] ||
        $n['smt4'] < $n['kkm'] || $n['smt5'] < $n['kkm'] || $n['uas'] < $n['kkm']
    ) {
        $status = "Tidak Lulus";
    }

    // Menambahkan data nilai dan rata-rata ke dalam array
    $n['rata_rata'] = round($rata_rata, 2);
    $nilai_array[] = $n;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Lulus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 40px; }
        .judul { text-align: center; font-size: 18px; font-weight: bold; }
        .table-bordered td, .table-bordered th { border: 1px solid #000; padding: 5px; }
        .kop { text-align: center; }
        .ttd { margin-top: 50px; text-align: right; }
        .btn-print { position: fixed; top: 20px; right: 20px; z-index: 999; }
        @media print {
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

<!-- Tombol Cetak -->
<button onclick="window.print()" class="btn btn-success btn-print">Download / Cetak PDF</button>

<!-- Kop Surat -->
<div class="kop">
    <img src="../assets/kop_surat.png" width="100%">
</div>

<!-- Judul -->
<p class="judul mt-3">SURAT KETERANGAN LULUS<br>No. 420/&nbsp;123/SMPN4/VI/2025</p>

<!-- Identitas -->
<p>Berdasarkan hasil Rapat Dewan Guru SMPN 4 Kota Solok tanggal 2 Juni 2025 dan Kriteria Kelulusan yang telah ditetapkan, maka siswa berikut dinyatakan:</p>

<table class="mb-3">
    <tr><td>Nama</td><td>: <b><?= $siswa['nama']; ?></b></td></tr>
    <tr><td>Tempat, Tanggal Lahir</td><td>: <?= $siswa['tempat_lahir']; ?>, <?= $siswa['tanggal_lahir']; ?></td></tr>
    <tr><td>NIS / NISN</td><td>: <?= $siswa['nis']; ?> / <?= $siswa['nisn']; ?></td></tr>
</table>

<p class="text-center"><strong><?= strtoupper($status) ?></strong></p>

<!-- Tabel Nilai -->
<p>Dengan rincian nilai sebagai berikut:</p>

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Mata Pelajaran</th>
            <th>SMT 1</th>
            <th>SMT 2</th>
            <th>SMT 3</th>
            <th>SMT 4</th>
            <th>SMT 5</th>
            <th>UAS</th>
            <th>Rata-rata</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach ($nilai_array as $n) {
            $nama_mapel = isset($mapel_nama[$n['kode_mapel']]) ? $mapel_nama[$n['kode_mapel']] : $n['kode_mapel'];
            $ket = ($n['smt1'] >= $n['kkm'] && $n['smt2'] >= $n['kkm'] && $n['smt3'] >= $n['kkm'] &&
                    $n['smt4'] >= $n['kkm'] && $n['smt5'] >= $n['kkm'] && $n['uas'] >= $n['kkm']) ? 'Lulus' : 'Tidak Lulus';

            echo "<tr>
                <td>$no</td>
                <td>$nama_mapel</td>
                <td>{$n['smt1']}</td>
                <td>{$n['smt2']}</td>
                <td>{$n['smt3']}</td>
                <td>{$n['smt4']}</td>
                <td>{$n['smt5']}</td>
                <td>{$n['uas']}</td>
                <td>{$n['rata_rata']}</td>
                <td>$ket</td>
            </tr>";
            $no++;
        }
        ?>
    </tbody>
</table>

<!-- Tanda Tangan -->
<div class="ttd">
    Solok, 2 Juni 2025<br>
    Kepala SMPN 4 Kota Solok<br><br><br>
    <b><u>Drs. NAMA KEPALA SEKOLAH</u></b><br>
    NIP. 1960XXXXXXXXX
</div>

</body>
</html>
