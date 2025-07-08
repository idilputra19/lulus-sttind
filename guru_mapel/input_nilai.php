<?php
session_start();
include('../includes/koneksi.php');

// Cek role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'guru_mapel') {
    header('Location: ../login.php');
    exit();
}

$kode_mapel = $_SESSION['kode_mapel']; // Dari session

// Menangani input atau update nilai
if (isset($_POST['update_nilai'])) {
    foreach ($_POST['nis'] as $key => $nis) {
        $nis = mysqli_real_escape_string($conn, $nis);
        $smt1 = mysqli_real_escape_string($conn, $_POST['smt1'][$key]);
        $smt2 = mysqli_real_escape_string($conn, $_POST['smt2'][$key]);
        $smt3 = mysqli_real_escape_string($conn, $_POST['smt3'][$key]);
        $smt4 = mysqli_real_escape_string($conn, $_POST['smt4'][$key]);
        $smt5 = mysqli_real_escape_string($conn, $_POST['smt5'][$key]);
        $uas  = mysqli_real_escape_string($conn, $_POST['uas'][$key]);

        $update_sql = "UPDATE nilai 
                       SET smt1 = '$smt1', smt2 = '$smt2', smt3 = '$smt3', smt4 = '$smt4', smt5 = '$smt5', uas = '$uas' 
                       WHERE nis = '$nis' AND kode_mapel = '$kode_mapel'";
        mysqli_query($conn, $update_sql);
    }

    $success_message = "Nilai berhasil diperbarui!";
}

// Ambil data terbaru dari database
$sql = "SELECT siswa.nis, siswa.nama, nilai.smt1, nilai.smt2, nilai.smt3, nilai.smt4, nilai.smt5, nilai.uas
        FROM siswa
        JOIN nilai ON siswa.nis = nilai.nis
        WHERE nilai.kode_mapel = '$kode_mapel'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include('../template_head.php'); ?>
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center mb-4 text-gray-800">Input Nilai Mata Pelajaran</h3>

    <?php if (isset($success_message)) : ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="input_nilai.php">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Smt 1</th>
                    <th>Smt 2</th>
                    <th>Smt 3</th>
                    <th>Smt 4</th>
                    <th>Smt 5</th>
                    <th>UAS</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><input type="text" class="form-control" name="nis[]" value="<?php echo $row['nis']; ?>" readonly></td>
                        <td><?php echo $row['nama']; ?></td>
                    <td><input type="number" class="form-control" name="smt1[]" value="<?php echo $row['smt1']; ?>" min="1" max="100" required></td>
<td><input type="number" class="form-control" name="smt2[]" value="<?php echo $row['smt2']; ?>" min="1" max="100" required></td>
<td><input type="number" class="form-control" name="smt3[]" value="<?php echo $row['smt3']; ?>" min="1" max="100" required></td>
<td><input type="number" class="form-control" name="smt4[]" value="<?php echo $row['smt4']; ?>" min="1" max="100" required></td>
<td><input type="number" class="form-control" name="smt5[]" value="<?php echo $row['smt5']; ?>" min="1" max="100" required></td>
<td><input type="number" class="form-control" name="uas[]" value="<?php echo $row['uas']; ?>" min="1" max="100" required></td>
   
                    
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="text-center">
            <button type="submit" name="update_nilai" class="btn btn-success w-100">Update Nilai</button>
        </div>
    </form>
</div>

<?php include('../template_foot.php'); ?>
</body>
</html>


<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        let isValid = true;
        document.querySelectorAll('input[type="number"]').forEach(function(input) {
            const value = parseInt(input.value);
            if (value < 1 || value > 100) {
                alert("Nilai harus antara 1 dan 100!");
                isValid = false;
            }
        });

        if (!isValid) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
</script>
