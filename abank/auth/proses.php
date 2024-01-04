<?php
require_once '../_config.php';

if (isset($_POST['registrasi_nasabah'])) {
    // Tangkap data dari formulir
    $nik = $_POST['nik'];
    $nama = strtolower($_POST['nama']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = strtolower($_POST['alamat']);
    $no_hp = $_POST['no_hp'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validasi Format Nomor HP
    if (!preg_match('/^[0-9]{10,15}$/', $no_hp)) {
        echo "<script>alert('Format Nomor HP tidak valid. Harap masukkan nomor HP dengan benar.'); window.location='login.php';</script>";
    } else {
        // Cek apakah Nomor HP sudah terdaftar
        $sql_cek_no_hp = mysqli_query($conn, "SELECT * FROM nasabah WHERE no_hp = '$no_hp'") or die(mysqli_error($conn));

        if (mysqli_num_rows($sql_cek_no_hp) > 0) {
            echo "<script>alert('Nomor HP sudah terdaftar!'); window.location='login.php';</script>";
        } else {
            // Cek apakah NIK sudah pernah diinput
            $sql_cek_nik = mysqli_query($conn, "SELECT * FROM nasabah WHERE nik = '$nik'") or die(mysqli_error($conn));

            if (mysqli_num_rows($sql_cek_nik) > 0) {
                echo "<script>alert('Nomor Identitas sudah pernah diinput!'); window.location='login.php';</script>";
            } else {
                $query = "INSERT INTO nasabah (nik, nama, jenis_kelamin, tanggal_lahir, alamat, no_hp, password) VALUES ('$nik', '$nama', '$jenis_kelamin', '$tanggal_lahir', '$alamat', '$no_hp', '$password')";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    echo "<script>alert('Registrasi berhasil. Silakan login.'); window.location.href='login.php';</script>";
                } else {
                    echo "<script>alert('Registrasi gagal.'); window.location.href='login.php';</script>";
                }
            }
        }
    }
} elseif (isset($_POST['login'])) {
    // Tangkap data dari formulir login
    $login_no_hp = $_POST['no_hp'];
    $login_password = $_POST['password'];

    // Query SQL untuk mengambil data dari tabel admin berdasarkan no_hp
    $query_admin = "SELECT * FROM admin WHERE no_hp = '$login_no_hp'";
    $result_admin = mysqli_query($conn, $query_admin);
    $admin = mysqli_fetch_assoc($result_admin);

    // Query SQL untuk mengambil data dari tabel karyawan berdasarkan no_hp
    $query_karyawan = "SELECT * FROM karyawan WHERE no_hp = '$login_no_hp'";
    $result_karyawan = mysqli_query($conn, $query_karyawan);
    $karyawan = mysqli_fetch_assoc($result_karyawan);

    // Query SQL untuk mengambil data dari tabel nasabah berdasarkan no_hp
    $query_nasabah = "SELECT * FROM nasabah WHERE no_hp = '$login_no_hp'";
    $result_nasabah = mysqli_query($conn, $query_nasabah);
    $nasabah = mysqli_fetch_assoc($result_nasabah);

    // Periksa kecocokan password dan role di masing-masing tabel
    if ($admin && password_verify($login_password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id_admin'];
        $_SESSION['user_nama'] = $admin['nama'];
        $_SESSION['user_foto'] = $admin['foto'];
        $_SESSION['user_role'] = 'admin';
        $sayHay = ucwords($admin['nama']);
        echo "<script>alert('Login berhasil. Selamat datang, $sayHay!'); window.location='" . base_url() . "';</script>";
    } elseif ($karyawan && password_verify($login_password, $karyawan['password'])) {
        $_SESSION['user_id'] = $karyawan['id_karyawan'];
        $_SESSION['user_nama'] = $karyawan['nama'];
        $_SESSION['user_foto'] = $karyawan['foto'];
        $_SESSION['user_role'] = 'karyawan';
        $sayHay = ucwords($karyawan['nama']);
        echo "<script>alert('Login berhasil. Selamat datang, $sayHay!'); window.location='" . base_url() . "';</script>";
    } elseif ($nasabah && password_verify($login_password, $nasabah['password'])) {
        $_SESSION['user_id'] = $nasabah['id_nasabah'];
        $_SESSION['user_nama'] = $nasabah['nama'];
        $_SESSION['user_foto'] = $nasabah['foto'];
        $_SESSION['user_role'] = 'nasabah';
        echo "<script>window.location='" . base_url() . "';</script>";
    } else {
        // Password atau role tidak cocok
        echo "<script>alert('Login gagal. No HP, password, atau role salah.'); window.location.href='login.php';</script>";
    }

    // Tutup koneksi database
    mysqli_close($conn);
} else {
    // Jika formulir tidak dikirim, tampilkan pesan kesalahan
    echo "Terjadi kesalahan. Pastikan Anda mengisi formulir dengan benar.";
}
