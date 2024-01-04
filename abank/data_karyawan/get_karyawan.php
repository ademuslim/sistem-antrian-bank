<?php
require_once "../_config.php";

if (isset($_GET['id'])) {
    $idKaryawan = $_GET['id'];

    // Query untuk mengambil data karyawan berdasarkan ID
    $query = "SELECT * FROM karyawan WHERE id_karyawan = '$idKaryawan'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $dataKaryawan = mysqli_fetch_assoc($result);
        echo json_encode($dataKaryawan);
    } else {
        echo json_encode(['error' => 'Query error']);
    }
} else {
    echo json_encode(['error' => 'ID karyawan tidak tersedia']);
}
?>
