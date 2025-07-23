<?php
include('../includes/koneksi.php');

// Periksa apakah NIS ada di GET request
if (isset($_GET['nis'])) {
    $nis = $_GET['nis'];

    // Query untuk mendapatkan nama dan kelas siswa berdasarkan NIS
    $query = "SELECT nama, kelas FROM siswa WHERE nis = '$nis'";
    $result = mysqli_query($conn, $query);

    // Jika NIS ditemukan, kembalikan nama dan kelas siswa dalam format JSON
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Mengembalikan data sebagai JSON
        echo json_encode(array('nama' => $row['nama'], 'kelas' => $row['kelas']));
    } else {
        echo ''; // Jika NIS tidak ditemukan, kirimkan string kosong
    }
} else {
    echo ''; // Jika NIS tidak dikirimkan
}
?>
