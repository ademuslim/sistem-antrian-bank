<?php
include '_header.php';

$id = $_SESSION['user_id'];
$tanggal_sekarang = date("Y-m-d");

// Query untuk mengambil informasi antrian dan nama loket
$query = "SELECT tanggal
        FROM antrian 
        WHERE antrian.id_nasabah = '$id'
        AND antrian.status_antrian IN ('menunggu', 'proses')
        AND antrian.tanggal = '$tanggal_sekarang'
        ORDER BY antrian.id_antrian DESC 
        LIMIT 1";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

// Inisialisasi variabel $showWarning
$showWarning = false;

// Periksa apakah terdapat hasil dari query
if ($result) {
    // Jika ada baris hasil
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        // Data ditemukan, element form di-disable dan tampilkan div warning
        $disableForm = true;
        $showWarning = true;
    } else {
        // Tidak ada data ditemukan, element form tidak di-disable, dan div warning tidak ditampilkan
        $disableForm = false;
    }
} else {
    // Query gagal
    $disableForm = false;
    $showWarning = false; // Pastikan variabel ini diatur menjadi false jika query gagal
}
?>

<main>
    <section class="form-section">
        <article class="form-container">
            <header class="form-header">
                <h2 class="form-title">Ambil Antrian</h2>
                <!-- <a href="index.php"><span class="material-symbols-outlined">arrow_back</span>KEMBALI</a> -->
            </header>
            
            <form id="ambilAntrianForm" action="proses.php" method="post">
                <label for="jenis_antrian">Pilih Jenis Antrian:</label>
                <select name="jenis_antrian" id="jenis_antrian" class="form-control" required>
                    <option value="" disabled selected>-- Pilih Jenis Antrian --</option>
                    <!-- Looping data bagian karyawan-->
                    <?php
                    $sql_bagian = mysqli_query($conn, "SELECT DISTINCT bagian FROM karyawan") or die(mysqli_error($conn));
                    while ($data = mysqli_fetch_array($sql_bagian)) {
                        echo '<option value="' . $data['bagian'] . '">
                        ' . strtoupper($data['bagian']) . '</option>';
                    }
                    ?>
                </select>
                <button type="submit" name="ambil_antrian" id="ambilAntrianBtn">Kirim</button>
            </form>
            <!-- Warning section -->
            <div id="warningDiv" <?php if ($showWarning) echo 'style="display: block;"'; ?>>
                <!-- Your warning message goes here -->
                Anda sudah mengambil nomor antrian.
            </div>
        </article>
    </section>

    <section class="data-section">
        <article class="data-container">
            <header class="data-header">
                <h2 class="data-title">Antrian Menunggu</h2>
            </header>
            <?php
            $query = "SELECT l.nama_loket AS nama_loket, COUNT(a.id_antrian) AS total_antrian_menunggu
                    FROM loket l
                    LEFT JOIN antrian a ON l.id_loket = a.id_loket
                    WHERE a.status_antrian = 'menunggu'
                    GROUP BY l.nama_loket";

            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="data-row">
                <p><?= ucwords($row['nama_loket']); ?></p>
                <p><?= $row['total_antrian_menunggu']; ?></p>
            </div>
            <?php
            }
            ?>
        </article>
    </section>
</main>

<script>
    // Menonaktifkan form dan menyembunyikan div warning menggunakan JavaScript
    <?php if ($disableForm) : ?>
        document.getElementById("ambilAntrianForm").addEventListener("submit", function (event) {
            event.preventDefault();
            alert("Anda sudah mengambil antrian yang sedang menunggu.");
        });

        // Menonaktifkan tombol submit
        document.getElementById("ambilAntrianBtn").disabled = true;

        // Menyembunyikan div warning
        document.getElementById("warningDiv").style.display = "block";
    <?php else : ?>
        document.getElementById("warningDiv").style.display = "none";
    <?php endif; ?>
</script>

<?php
include '_footer.php';
?>

