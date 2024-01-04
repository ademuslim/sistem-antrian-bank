<?php
include '_header.php';

// notifikasi ambil antrian berhasil
if (isset($_SESSION['ambil_antrian_status'])) {
    if ($_SESSION['ambil_antrian_status'] === 'berhasil') {
        echo '<div class="notification success">Antrian berhasil diambil</div>';
        // ... (kode lainnya)
    } elseif ($_SESSION['ambil_antrian_status'] === 'gagal') {
        echo '<div class="notification error">' . $_SESSION['notification'] . '</div>';
        // ... (kode lainnya)
    }

    // Bersihkan session setelah notifikasi ditampilkan
    unset($_SESSION['ambil_antrian_status']);
    unset($_SESSION['notification']);
}

$id = $_SESSION['user_id'];
?>

<main>
    <section class="data-section">
        <article class="data-container">
            <header class="data-header">
                <h3 class="main-title">Nomor Antrian Anda</h3>
            </header>
            <?php

            // Query untuk mengambil informasi antrian dan nama loket
            $query = "SELECT antrian.*, loket.nama_loket 
                    FROM antrian 
                    LEFT JOIN loket ON antrian.id_loket = loket.id_loket
                    WHERE antrian.id_nasabah = '$id'
                    AND antrian.status_antrian IN ('menunggu', 'proses') 
                    ORDER BY antrian.id_antrian DESC 
                    LIMIT 1";

            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

            // Periksa apakah terdapat hasil dari query
            if ($result && $row = mysqli_fetch_assoc($result)) {
                $nomor_antrian = $row['kode_antrian'];
                $loket_tujuan = $row['nama_loket'];
                $status_antrian = $row['status_antrian'];
            ?>
            <div class="antrian-info">
                <!-- Menampilkan informasi nomor antrian -->
                <p>No. Antrian</p>
                <span class="antrian-info-item strong"><?= strtoupper($nomor_antrian) ?></span>

                <!-- Menampilkan informasi loket tujuan -->
                <p>Loket Tujuan</p>
                <span class="antrian-info-item"><?= strtoupper($loket_tujuan) ?></span>

                <!-- Menampilkan informasi status antrian -->
                <p>Status Antrian</p>
                <span class="antrian-info-item"><?= strtoupper($status_antrian) ?></span>
            <?php
            } else {
                // Tampilkan pesan jika tidak ada data antrian
                echo "<p>Anda tidak memiliki antrian saat ini.</p>";
            }
            ?>
            </div>
            </div>
        </article>
    </section>

    <section class="data-section">
        <article class="data-container">
            <header class="data-header">
                <h3 class="main-title">Antrian Sedang Berlangsung</h3>
            </header>
            <?php

            // Query untuk mendapatkan id_loket dari nomor antrian terbaru yang sedang menunggu oleh nasabah
            $query_id_loket = "SELECT id_loket FROM antrian WHERE id_nasabah = '$id' AND status_antrian IN ('menunggu', 'proses') ORDER BY id_antrian DESC LIMIT 1";
            $result_id_loket = mysqli_query($conn, $query_id_loket) or die(mysqli_error($conn));

            // Periksa apakah terdapat hasil dari query
            if ($result_id_loket && $row_id_loket = mysqli_fetch_assoc($result_id_loket)) {
                $id_loket = $row_id_loket['id_loket'];

                // Query untuk mendapatkan nomor antrian terbaru dengan status 'proses' di loket yang sama
                $query_nomor_antrian_terbaru = "SELECT kode_antrian FROM antrian WHERE id_loket = '$id_loket' AND status_antrian = 'proses' ORDER BY id_antrian DESC LIMIT 1";
                $result_nomor_antrian_terbaru = mysqli_query($conn, $query_nomor_antrian_terbaru) or die(mysqli_error($conn));

                // Periksa apakah terdapat hasil dari query
                if ($result_nomor_antrian_terbaru && $row_nomor_antrian_terbaru = mysqli_fetch_assoc($result_nomor_antrian_terbaru)) {
                    $nomor_antrian_terbaru = strtoupper($row_nomor_antrian_terbaru['kode_antrian']);
                    
                    // Tampilkan nomor antrian terbaru
                    echo "Nomor Antrian Yang Sedang Dilayani: <span id='antrian-proses'>$nomor_antrian_terbaru</span>";
                } else {
                    // Tampilkan pesan jika tidak ada nomor antrian dengan status 'proses'
                    echo "Belum ada nomor antrian yang di proses.";
                }
            } else {
                // Tampilkan pesan jika tidak ada nomor antrian yang sedang menunggu
                echo "Tidak ada nomor antrian yang sedang menunggu saat ini.";
            }
            ?>

        </article>
    </section>
</main>

<script>
    setTimeout(function () {
        var notifications = document.getElementsByClassName('notification');
        for (var i = 0; i < notifications.length; i++) {
            notifications[i].remove();
        }
    }, 5000);
</script>

<?php
include '_footer.php';
?>
