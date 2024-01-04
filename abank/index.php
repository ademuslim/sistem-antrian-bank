<?php
require_once "_config.php";

// Jika ada sesi, cek user_role dan arahkan sesuai peran
if (isset($_SESSION['user_nama'])) {
    if (isset($_SESSION['user_role'])) {
        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'karyawan') {
            echo "<script>window.location='" . base_url('dashboard') . "';</script>";
        } elseif ($_SESSION['user_role'] === 'nasabah') {
            echo "<script>window.location='" . base_url('ambil_antrian_nasabah/ambil_antrian.php') . "';</script>";
        }
    }
} else {
    // Jika tidak ada sesi, arahkan ke halaman login
    echo "<script>window.location='" . base_url('auth/login.php') . "';</script>";
}
?>
