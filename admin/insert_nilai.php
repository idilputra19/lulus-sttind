<?php
session_start();
include('../includes/koneksi.php');

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nis = $_POST['nis'];  // NIS dipilih dari dropdown
    $kode_mapel = $_POST['kode_mapel'];
    $smt1 = $_POST['smt1'];
    $smt2 = $_POST['smt2'];
    $smt3 = $_POST['smt3'];
    $smt4 = $_POST['smt4'];
    $smt5 = $_POST['smt5'];
    $uas = $_POST['uas'];
    $tahun_ajaran = '2024/2025';  // Ganti sesuai tahun ajaran yang aktif

    // Validasi: pastikan nilai tidak lebih dari 100
    if ($smt1 > 100 || $smt2 > 100 || $smt3 > 100 || $smt4 > 100 || $smt5 > 100 || $uas > 100) {
        echo "<script>
                alert('Nilai tidak boleh lebih dari 100!');
                window.location.href = 'data_nilai.php';
              </script>";
        exit();
    }

    // Cek apakah siswa sudah memiliki nilai untuk mata pelajaran yang dipilih
    $query_check = "
        SELECT * FROM nilai 
        WHERE nis = '$nis' 
        AND kode_mapel = '$kode_mapel'
        AND tahun_ajaran = '$tahun_ajaran'
    ";
    $result_check = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Jika sudah ada nilai untuk mata pelajaran tersebut, tampilkan pesan error dan redirect
        echo "<script>
                alert('Nilai untuk mata pelajaran ini sudah ada untuk siswa tersebut.');
                window.location.href = 'data_nilai.php';
              </script>";
    } else {
        // Query untuk memasukkan data
        $query = "
            INSERT INTO nilai (nis, kode_mapel, smt1, smt2, smt3, smt4, smt5, uas, tahun_ajaran)
            VALUES ('$nis', '$kode_mapel', '$smt1', '$smt2', '$smt3', '$smt4', '$smt5', '$uas', '$tahun_ajaran')
        ";

        if (mysqli_query($conn, $query)) {
            // Jika berhasil, tampilkan pesan sukses dan redirect
            echo "<script>
                    alert('Nilai berhasil ditambahkan!');
                    window.location.href = 'data_nilai.php';
                  </script>";
        } else {
            // Jika terjadi kesalahan, tampilkan pesan error dan redirect
            echo "<script>
                    alert('Terjadi kesalahan: " . mysqli_error($conn) . "');
                    window.location.href = 'data_nilai.php';
                  </script>";
        }
    }
}
?>
