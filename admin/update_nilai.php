<?php
session_start();
include('../includes/koneksi.php');

// Ensure only the admin can access this page
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Check if the form has been submitted and required data exists
if (isset($_POST['nis']) && isset($_POST['kode_mapel']) && isset($_POST['smt1']) && isset($_POST['smt2']) && isset($_POST['smt3']) && isset($_POST['smt4']) && isset($_POST['smt5']) && isset($_POST['uas'])) {
    
    // Sanitize and assign form values to variables
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $kode_mapel = mysqli_real_escape_string($conn, $_POST['kode_mapel']);
    $smt1 = mysqli_real_escape_string($conn, $_POST['smt1']);
    $smt2 = mysqli_real_escape_string($conn, $_POST['smt2']);
    $smt3 = mysqli_real_escape_string($conn, $_POST['smt3']);
    $smt4 = mysqli_real_escape_string($conn, $_POST['smt4']);
    $smt5 = mysqli_real_escape_string($conn, $_POST['smt5']);
    $uas = mysqli_real_escape_string($conn, $_POST['uas']);

    // Update the grades in the database
    $update_query = "
        UPDATE nilai 
        SET smt1 = '$smt1', smt2 = '$smt2', smt3 = '$smt3', smt4 = '$smt4', smt5 = '$smt5', uas = '$uas'
        WHERE nis = '$nis' AND kode_mapel = '$kode_mapel'
    ";

    if (mysqli_query($conn, $update_query)) {
        // Redirect back to the previous page after successful update
        header("Location: data_nilai.php?update=success");
        exit();
    } else {
        // If there's an error during update
        echo "<div class='alert alert-danger'>Error updating data: " . mysqli_error($conn) . "</div>";
    }
} else {
    // If data is missing from the form
    echo "<div class='alert alert-danger'>Invalid data received!</div>";
}
?>
