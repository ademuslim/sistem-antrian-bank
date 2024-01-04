<?php
include '_config.php';

// Jika tidak ada session, arahkan ke login
if (!isset($_SESSION['user_nama'])) {
    echo "<script>window.location='" . base_url('auth/login.php') . "';</script>";
}

// Hak akses menu admin atau karyawan
$userRole = $_SESSION['user_role'];
$idKaryawan = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AntrianBank</title>
    <!-- Google icon link -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="<?= base_url('_assets/css/style.css'); ?>">
</head>

<body class="fade-out">
    <aside>
        <div class="logo">
            <span class="material-symbols-outlined">
                account_balance
            </span>
        </div>
        <ul class="menu">
            <li <?= setActivePage('dashboard'); ?>>
                <a href="<?= base_url('dashboard'); ?>">
                    <span class="material-symbols-outlined">
                        grid_view
                    </span>
                    <span class="menu-item">Dashboard</span>
                </a>
            </li>
            <li <?= setActivePage('data_antrian'); ?>>
                <a href="<?= base_url('data_antrian'); ?>">
                    <span class="material-symbols-outlined">
                        list
                    </span>
                    <span class="menu-item">Data Antrian</span>
                </a>
            </li>
            <li <?= setActivePage('riwayat_antrian'); ?>>
                <a href="<?= base_url('riwayat_antrian'); ?>">
                    <span class="material-symbols-outlined">
                        history
                    </span>
                    <span class="menu-item">Riwayat Antrian</span>
                </a>
            </li>
            <!-- Hanya tampilkan untuk pengguna dengan role admin -->
            <?php if ($userRole === 'admin') : ?>
                <li <?= setActivePage('loket_antrian'); ?>>
                    <a href="<?= base_url('loket_antrian'); ?>">
                        <span class="material-symbols-outlined">
                            computer
                        </span>
                        <span class="menu-item">Loket Antrian</span>
                    </a>
                </li>
                <li <?= setActivePage('data_karyawan'); ?>>
                    <a href="<?= base_url('data_karyawan'); ?>">
                        <span class="material-symbols-outlined">
                            person
                        </span>
                        <span class="menu-item">Data Karyawan</span>
                    </a>
                </li>
                <li <?= setActivePage('data_nasabah'); ?>>
                    <a href="<?= base_url('data_nasabah'); ?>">
                        <span class="material-symbols-outlined">
                            groups
                        </span>
                        <span class="menu-item">Data Nasabah</span>
                    </a>
                </li>
                <li <?= setActivePage('backup_db'); ?>>
                    <a href="<?= base_url('backup_db'); ?>">
                        <span class="material-symbols-outlined">
                            database
                        </span>
                        <span class="menu-item">Backup Database</span>
                    </a>
                </li>
            <?php endif; ?>
            <li class="logout">
                <a href="<?= base_url('auth/logout.php'); ?>">
                    <span class="material-symbols-outlined">
                        logout
                    </span>
                    <span class="menu-item">Logout</span>
                </a>
            </li>
        </ul>
    </aside>

    <div class="container">
        <header>
            <div class="header-title">
                <h1>BANK XYZ</h1>
                <span><?= tanggalIndonesia(); ?></span>
            </div>
            <div class="user-info">
                <?php
                if ($userRole === 'karyawan' && isset($idKaryawan)) {
                    $query = "SELECT id_loket, nama_loket, status_loket FROM loket WHERE id_karyawan = '$idKaryawan'";
                    $result = mysqli_query($conn, $query);

                    if ($result) {
                        $loketData = mysqli_fetch_assoc($result);

                        if ($loketData) {
                            $loketId = $loketData['id_loket'];
                            $loketName = strtoupper($loketData['nama_loket']);
                            $loketStatus = $loketData['status_loket'];
                        ?>
                            <div class="loket-info">
                                <div class="user-log-loket">
                                    <span><?= $loketName . ' :'; ?></span>
                                    <span class="status-loket <?= $loketStatus === 'aktif' ? 'status-aktif' : 'status-tutup'; ?>">
                                        <?= strtoupper($loketStatus); ?>
                                    </span>
                                </div>
                                <div class="toggle-loket-status">
                                    <form method="post">
                                        <input type="submit" name="submit" value="<?= $loketStatus === 'aktif' ? 'TUTUP' : 'BUKA'; ?>" class="btn-status-loket <?= $loketStatus === 'aktif' ? 'warning-button' : 'success-button'; ?>">
                                    </form>
                                </div>
                            </div>
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
                                // Toggle status dan update
                                $newStatus = ($loketStatus == 'aktif') ? 'tutup' : 'aktif';
                                $statusUpdated = updateLoketStatus($loketId, $newStatus);

                                if ($statusUpdated) {
                                    // Redirect kembali ke halaman sebelumnya
                                    echo "<script>window.history.back();</script>";
                                } else {
                                    echo 'Gagal mengupdate status loket.';
                                }
                            }
                        } else {
                            echo 'Data loket tidak ditemukan.';
                        }
                    } else {
                        echo 'Error: ' . mysqli_error($conn);
                    }
                }
                ?>
                <span class="user-log-name"><?= ucwords($_SESSION['user_nama']); ?></span>

                <img src="../_assets/image/photo/<?= $_SESSION['user_foto']; ?>" alt="photo">
            </div>
        </header>
