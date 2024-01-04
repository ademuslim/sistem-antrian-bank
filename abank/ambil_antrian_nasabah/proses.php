<?php
require_once "../_config.php";

// // Pengecekan apakah ada session role dan no_hp_pengguna
// if (!isset($_SESSION['role']) || !isset($_SESSION['no_hp_pengguna'])) {
//     // Jika tidak ada session, arahkan ke halaman login
//     header("Location: " . base_url('auth/login.php'));
//     exit();
// }

// Pengecekan role admin atau karyawan
if ($_SESSION['user_role'] !== 'nasabah') {
    // Jika role bukan nasabah
    header("Location: " . base_url('auth/login.php'));
    exit();
}

// Ambil input dari formulir
$jenis_antrian = $_POST['jenis_antrian'];
$id_nasabah = $_SESSION['user_id']; 

// Eksekusi query untuk mendapatkan id_loket yang sesuai
// $query_pilih_loket = "SELECT l.id_loket
//                     FROM loket l
//                     LEFT JOIN (
//                         SELECT id_loket, COUNT(*) AS jumlah_antrian
//                         FROM antrian
//                         GROUP BY id_loket
//                     ) a ON l.id_loket = a.id_loket
//                     WHERE l.jenis_antrian = '$jenis_antrian'
//                     ORDER BY jumlah_antrian ASC, l.id_loket ASC
//                     LIMIT 1";

$query_pilih_loket = "SELECT l.id_loket
                    FROM loket l
                    LEFT JOIN (
                        SELECT id_loket, COUNT(*) AS jumlah_antrian
                        FROM antrian
                        GROUP BY id_loket
                    ) a ON l.id_loket = a.id_loket
                    LEFT JOIN karyawan k ON l.id_karyawan = k.id_karyawan
                    WHERE k.bagian = '$jenis_antrian'
                    AND l.status_loket = 'aktif'
                    ORDER BY jumlah_antrian ASC, l.id_loket ASC
                    LIMIT 1";

$result_pilih_loket = mysqli_query($conn, $query_pilih_loket);

if ($result_pilih_loket && $row_loket = mysqli_fetch_assoc($result_pilih_loket)) {
    $id_loket = $row_loket['id_loket'];

    // Eksekusi query untuk mendapatkan kode_loket dan nama_loket
    $query_kode_loket = "SELECT kode_loket, nama_loket FROM loket WHERE id_loket = '$id_loket'";
    $result_kode_loket = mysqli_query($conn, $query_kode_loket);

    if ($result_kode_loket && $row_kode_loket = mysqli_fetch_assoc($result_kode_loket)) {
        $kode_loket = $row_kode_loket['kode_loket'];
        $nama_loket = $row_kode_loket['nama_loket'];

        // Eksekusi query untuk mendapatkan kode antrian custom
        $query_jumlah_antrian = "SELECT COUNT(*) AS total_antrian FROM antrian WHERE id_loket = '$id_loket'";
        $result_jumlah_antrian = mysqli_query($conn, $query_jumlah_antrian);

        if ($result_jumlah_antrian && $row_jumlah_antrian = mysqli_fetch_assoc($result_jumlah_antrian)) {
            $total_antrian = $row_jumlah_antrian['total_antrian'];

            $nomor_antrian = str_pad($total_antrian + 1, 3, '0', STR_PAD_LEFT);
            $kode_antrian = $kode_loket . '.' . $nomor_antrian; // Kode loket ditambahkan
            $tanggal_sekarang = date('Y-m-d'); // Ambil tanggal saat ini
            $query_tambah_antrian = "INSERT INTO antrian (kode_antrian, tanggal, id_loket, id_nasabah)
                                    VALUES (
                                        '$kode_antrian',
                                        '$tanggal_sekarang',
                                        '$id_loket',
                                        '$id_nasabah'
                                    )";
            mysqli_query($conn, $query_tambah_antrian);

            $_SESSION['ambil_antrian_status'] = 'berhasil';
            $_SESSION['kode_antrian'] = $kode_antrian;
            $_SESSION['jenis_antrian'] = $jenis_antrian;
            $_SESSION['nama_loket'] = $nama_loket;
            $_SESSION['id_loket'] = $id_loket;

            header("Location: info_antrian.php");
            exit();
        }
    }
}
// Arahkan ke halaman antrian_penuh.php jika terjadi kesalahan atau tidak dapat menambahkan antrian
$_SESSION['ambil_antrian_status'] = 'gagal';
$_SESSION['notification'] = 'Loket sedang tutup';
header("Location: index.html");
exit();
?>