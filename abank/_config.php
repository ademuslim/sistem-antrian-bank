<?php
session_start();

// Konfigurasi database
$host = "localhost"; // Ganti dengan host database Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$database = "antrian_bank"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi untuk membersihkan dan mencegah SQL injection
function sanitizeInput($input) {
    global $conn;
    return mysqli_real_escape_string($conn, $input);
}

// Fungsi untuk mendapatkan base URL
function base_url($url = null) {
    $base_url = "http://localhost/abank";
    if ($url != null) {
        return $base_url . "/" . $url;
    } else {
        return $base_url;
    }
}

function setActivePage($page)
{
    $current_page = $_SERVER['REQUEST_URI'];
    $active_class = '';

    // Periksa apakah halaman saat ini mengandung string yang sesuai dengan halaman yang ditentukan
    if (str_contains($current_page, $page)) {
        $active_class = 'class="active"';
    }

    return $active_class;
}

function tanggalIndonesia() {
    // Set timezone ke Asia/Jakarta agar sesuai dengan waktu Indonesia Barat
    date_default_timezone_set('Asia/Jakarta');

    // Array untuk nama bulan dalam bahasa Indonesia
    $bulanIndonesia = array(
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );

    // Mendapatkan indeks bulan saat ini (0-11)
    $indexBulan = date('n') - 1;

    // Format tanggal dengan nama bulan dalam bahasa Indonesia
    $tanggal = date('j') . ' ' . $bulanIndonesia[$indexBulan] . ' ' . date('Y');

    // Kembalikan tanggal yang telah diformat
    return $tanggal;
}

// Fungsi untuk mengubah status loket
function updateLoketStatus($loketId, $newStatus)
{
    global $conn;

    $updateQuery = "UPDATE loket SET status_loket = '$newStatus' WHERE id_loket = '$loketId'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        return true;
    } else {
        return false;
    }
}

// Fungsi memberi style status antrian

function getStatusClass($status_antrian) {
    switch (strtolower($status_antrian)) {
        case 'batal':
            return 'status-batal';
        case 'selesai':
            return 'status-selesai';
        case 'tutup':
            return 'status-batal';
        case 'aktif':
            return 'status-selesai';
        default:
            return ''; // Tidak ada kelas tambahan
    }
}
?>
