<?php
require_once "../_config.php";

$id_karyawan_to_delete = $_GET['id'];

// Pengecekan apakah id karyawan terdaftar pada tabel loket
$cek_loket_query = "SELECT id_loket FROM loket WHERE id_karyawan = '$id_karyawan_to_delete'";
$cek_loket_result = mysqli_query($conn, $cek_loket_query);

if (mysqli_num_rows($cek_loket_result) > 0) {
    // Jika id karyawan terdaftar pada tabel loket, tampilkan pesan alert
    echo "<script>alert('Tidak dapat menghapus karyawan karena terdaftar pada loket. Ubah atau hapus terlebih dahulu data loket yang terkait.'); window.location='index.php';</script>";
    exit();
}

// Jika id karyawan tidak terdaftar pada tabel loket, lanjutkan dengan menghapus
mysqli_query($conn, "DELETE FROM karyawan WHERE id_karyawan = '$id_karyawan_to_delete'") or die(mysqli_error($conn));

// Setelah penghapusan, redirect ke halaman index.php
echo "<script>window.location='index.php';</script>";
?>
