<?php
require_once '../_config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AntrianBank | Bank XYZ</title>
    <link rel="stylesheet" href="../_assets/css/ambil-antrian-style.css">
</head>
<body>
    <header>
        <nav>
            <div class="brand-info">
                <h2 class="brand">AntrianBank</h2>
                <h3 class="bank-name">Bank XYZ</h3>
            </div>
            
            <ul>
                <li <?= setActivePage('ambil_antrian_nasabah/ambil_antrian.php'); ?>>
                    <a href="<?= base_url('ambil_antrian_nasabah/ambil_antrian.php'); ?>">AMBIL ANTRIAN</a>
                </li>
                <li <?= setActivePage('ambil_antrian_nasabah/info_antrian.php'); ?>>
                    <a href="<?= base_url('ambil_antrian_nasabah/info_antrian.php'); ?>">INFORMASI ANTRIAN</a>
                </li>
                <li <?= setActivePage('ambil_antrian_nasabah/bantuan.php'); ?>>
                    <a href="<?= base_url('ambil_antrian_nasabah/bantuan.php'); ?>">BANTUAN</a>
                </li>
                <li>
                    <a href="../auth/logout.php">LOGOUT</a>
                </li>
            </ul>
            <div class="profil">
                <p><?= ucwords($_SESSION['user_nama']); ?></p>
                <img src="../_assets/image/photo/<?= $_SESSION['user_foto']; ?>" alt="">
            </div>
        </nav>
    </header>