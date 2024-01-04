<?php
require_once "../_config.php";

// Ambil ID Antrian dari parameter GET
$idAntrian = $_GET['id'];

// Update status_antrian menjadi 'proses'
$sql = "UPDATE antrian SET status_antrian = 'proses' WHERE id_antrian = $idAntrian";

if (mysqli_query($conn, $sql)) {
    echo "Status Antrian diperbarui menjadi 'proses' berhasil";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

// Tutup koneksi
mysqli_close($conn);
?>
