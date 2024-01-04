<?php
include '../header.php';

$userId = $_SESSION['user_id']; // Digunakan jika role yang login adalah karyawan
$userRole = $_SESSION['user_role'];
?>
<?php if ($userRole === 'admin') : ?>
<?php
// Query untuk mendapatkan informasi antrian, loket, karyawan, dan nasabah
$query = "
    SELECT
        (SELECT COUNT(*) FROM antrian WHERE status_antrian = 'menunggu') AS totalAntrian,
        (SELECT COUNT(*) FROM loket) AS totalLoket,
        (SELECT COUNT(*) FROM karyawan) AS totalKaryawan,
        (SELECT COUNT(*) FROM nasabah) AS totalNasabah
";
// Eksekusi query
$result = mysqli_query($conn, $query);

// Periksa apakah query berhasil dijalankan
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

// Ambil hasil query
$row = mysqli_fetch_assoc($result);

// Ambil data dari hasil query
$totalAntrian = $row['totalAntrian'];
$totalLoket = $row['totalLoket'];
$totalKaryawan = $row['totalKaryawan'];
$totalNasabah = $row['totalNasabah'];
?>
        <main>
            <section class="dashboard-section">
                <!-- Card Antrian Menunggu -->
                <div class="dashboard-card">
                    <h2 class="card-title">ANTRIAN MENUNGGU</h2>
                    <p class="card-number"><?php echo $totalAntrian; ?></p>
                </div>

                <!-- Card Jumlah Loket -->
                <div class="dashboard-card">
                    <h2 class="card-title">LOKET</h2>
                    <p class="card-number"><?php echo $totalLoket; ?></p>
                </div>

                <!-- Card Jumlah Karyawan -->
                <div class="dashboard-card">
                    <h2 class="card-title">KARYAWAN</h2>
                    <p class="card-number"><?php echo $totalKaryawan; ?></p>
                </div>

                <!-- Card Jumlah Nasabah -->
                <div class="dashboard-card">
                    <h2 class="card-title">NASABAH</h2>
                    <p class="card-number"><?php echo $totalNasabah; ?></p>
                </div>
            </section>

            <div class="button-right">
                <button class="btn mt" onclick="window.location.href='add.php'"><span class="material-symbols-outlined">add</span>Tambah Admin</button>
            </div>
            <?php endif; ?>

            <?php if ($userRole === 'karyawan') : ?>
            <?php
            // Query untuk mendapatkan informasi antrian, loket, karyawan, dan nasabah berdasarkan id karyawan
            $query = "
                SELECT
                    (SELECT COUNT(*) FROM antrian
                        INNER JOIN loket ON antrian.id_loket = loket.id_loket
                        WHERE status_antrian = 'menunggu'
                        AND loket.id_karyawan = '$userId') AS totalAntrianMenunggu,
                    (SELECT COUNT(*) FROM antrian
                        INNER JOIN loket ON antrian.id_loket = loket.id_loket
                        WHERE status_antrian = 'selesai'
                        AND loket.id_karyawan = '$userId') AS totalAntrianSelesai,
                    (SELECT COUNT(*) FROM antrian
                        INNER JOIN loket ON antrian.id_loket = loket.id_loket
                        WHERE status_antrian = 'batal'
                        AND loket.id_karyawan = '$userId') AS totalAntrianBatal      
            ";
            // Eksekusi query
            $result = mysqli_query($conn, $query);

            // Periksa apakah query berhasil dijalankan
            if (!$result) {
                die("Query error: " . mysqli_error($conn));
            }

            // Ambil hasil query
            $row = mysqli_fetch_assoc($result);

            // Ambil data dari hasil query
            $totalAntrianMenunggu = $row['totalAntrianMenunggu'];
            $totalAntrianSelesai = $row['totalAntrianSelesai'];
            $totalAntrianBatal = $row['totalAntrianBatal'];
            ?>
            <section class="dashboard-section">
                <div class="dashboard-card">
                    <h2 class="card-title">ANTRIAN MENUNGGU</h2>
                    <p class="card-number"><?php echo $totalAntrianMenunggu; ?></p>
                </div>
                <div class="dashboard-card">
                    <h2 class="card-title">ANTRIAN SELESAI</h2>
                    <p class="card-number"><?php echo $totalAntrianSelesai; ?></p>
                </div>
                <div class="dashboard-card">
                    <h2 class="card-title">ANTRIAN BATAL</h2>
                    <p class="card-number"><?php echo $totalAntrianBatal; ?></p>
                </div>
            </section>

            <!-- Panggil antrian nasabah oleh karyawan -->
            <?php
            $sql = "SELECT a.kode_antrian, a.id_antrian, l.kode_loket
                FROM antrian a
                JOIN loket l ON a.id_loket = l.id_loket
                WHERE (a.status_antrian = 'menunggu' OR a.status_antrian = 'proses')
                AND l.id_karyawan = '$userId'
                ORDER BY a.id_antrian ASC
                LIMIT 1";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Ambil data dari hasil query
                $row = mysqli_fetch_assoc($result);
                $kode_antrian = $row['kode_antrian'];
                $id_antrian = $row['id_antrian'];
                $kode_loket = $row['kode_loket'];
                ?>

            <section class="antrian-section">
                <div class="data-card">
                    <h2 class="card-title">NO. ANTRIAN MENUNGGU</h2>
                    <p class="no-antrian"><?= strtoupper($kode_antrian); ?></p>
                    <button class="btn" onclick="panggilAntrian()"><span class="material-symbols-outlined">brand_awareness</span>Panggil Antrian</button>
                </div>

                <audio id="audioPanggil"></audio>

                <div class="data-card">
                    <h2 class="card-title">DETAIL ANTRIAN</h2>
                    <div class="antrian-details-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No. Handphone</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Periksa apakah variabel $id_antrian sudah ada
                                if (isset($id_antrian)) {
                                    $sql =  "SELECT * FROM nasabah
                                        INNER JOIN antrian ON nasabah.id_nasabah = antrian.id_nasabah
                                        WHERE antrian.id_antrian = '$id_antrian'";
                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($data = mysqli_fetch_assoc(($result))) { ?>
                                            <tr>
                                                <td><?= ucwords($data['nama']) ?></td>
                                                <td><?= ucfirst($data['jenis_kelamin']) ?></td>
                                                <td><?= $data['no_hp'] ?></td>
                                                <td class="btn-group">
                                                    <!-- Formulir untuk tombol update status_antrian -->
                                                    <form method="post" action="proses.php">
                                                        <input type="hidden" name="id_antrian" value="<?= $id_antrian; ?>">
                                                        <input type="hidden" name="status" value="selesai">
                                                        <input class="success-btn" type="submit" name="update_status" value="Selesai"></input>
                                                    </form>

                                                    <form method="post" action="proses.php">
                                                        <input type="hidden" name="id_antrian" value="<?= $id_antrian; ?>">
                                                        <input type="hidden" name="status" value="batal">
                                                        <input class="secondary-btn" type="submit" name="update_status" value="Batal"></input>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan=\"5\" align=\"center\">Data tidak ditemukan.</td></tr>";
                                    }
                                } else {
                                    echo '<tr><td colspan="4" align="center">Tidak ada data antrian yang sesuai.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
                } else {
                    // Tidak ada data yang sesuai dengan kondisi
                ?>
                <div class="antrian-card">
                    <span class="no-antrian">Tidak ada antrian.</span>
                </div>
                <?php
                }
                ?>
            </section>
                <?php endif; ?>
        </main>

        <script>
            // Fungsi panggil antrian
            function panggilAntrian() {
                var kodeAntrian = "<?= strtoupper($kode_antrian); ?>";
                var kodeLoket = "<?= strtoupper($kode_loket); ?>";
                var audioPanggil = document.getElementById("audioPanggil");

                // Tentukan durasi masing-masing suara
                var startDuration = 2000; // 3 detik
                var antrianDuration = 500; // .5 detik
                var loketDuration = 600; // .6 detik
                var kodeLoketDuration = 800; // .8 detik
                var endDuration = 1000; // 1 detik

                // Pemutaran awalan sebelum nomor antrian
                playSound('../_assets/voice/start.mp3', function () {
                    // Tunggu sebelum memulai pemutaran nomor antrian
                    setTimeout(function () {
                        playNextSound(kodeAntrian, 0, antrianDuration, function () {
                            // Jika sudah selesai menyebutkan nomor antrian, putar suara loket
                            playSound('../_assets/voice/loket.mp3', function () {
                                // Tunggu sebelum memulai pemutaran kode loket
                                setTimeout(function () {
                                    playNextSound(kodeLoket, 0, kodeLoketDuration, function () {
                                        // Jika sudah selesai menyebutkan kode loket, putar suara end
                                        playSound('../_assets/voice/end.mp3', function () {
                                            // Panggil fungsi untuk memperbarui status antrian
                                            updateStatusAntrian(<?= $id_antrian; ?>);
                                        });
                                    });
                                }, 0); // Sesuaikan dengan durasi karakter sebelumnya atau sesuai kebutuhan
                            });
                        });
                    }, startDuration); // Sesuaikan dengan durasi awalan
                });
            }

            function updateStatusAntrian(idAntrian) {
                // Kirim request AJAX untuk memperbarui status antrian
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Tanggapan dari server (jika diperlukan)
                        var response = xhr.responseText;
                        console.log("Status Antrian diperbarui menjadi 'proses'");
                    }
                };

                // Konfigurasi request
                xhr.open("GET", "update_status_antrian.php?id=" + idAntrian, true);

                // Kirim request
                xhr.send();
            }

            function playNextSound(kodeAntrian, index, duration, callback) {
                if (index < kodeAntrian.length) {
                    // Mendapatkan file suara berdasarkan karakter
                    var soundFile = getSoundFile(kodeAntrian.charAt(index));

                    // Setel file suara pada elemen audio
                    audioPanggil.src = soundFile;

                    // Memainkan suara
                    audioPanggil.play();

                    // Tunggu sebelum memainkan suara berikutnya
                    setTimeout(function () {
                        // Setel file suara kembali ke kosong setelah pemutaran
                        audioPanggil.src = "";

                        // Pemutaran suara karakter berikutnya
                        playNextSound(kodeAntrian, index + 1, duration, callback);
                    }, getSoundDuration(kodeAntrian.charAt(index)));
                } else {
                    // Jika sudah selesai menyebutkan nomor antrian, panggil callback
                    if (callback) {
                        setTimeout(callback, duration);
                    }
                }
            }

            function getSoundFile(character) {
                switch (character) {
                    case 'T':
                        return '../_assets/voice/t.mp3';
                    case 'C':
                        return '../_assets/voice/c.mp3';
                    case 'S':
                        return '../_assets/voice/s.mp3';
                    case '1':
                        return '../_assets/voice/1.mp3';
                    case '2':
                        return '../_assets/voice/2.mp3';
                    case '3':
                        return '../_assets/voice/3.mp3';
                    case '4':
                        return '../_assets/voice/4.mp3';
                    case '5':
                        return '../_assets/voice/5.mp3';
                    case '6':
                        return '../_assets/voice/6.mp3';
                    case '7':
                        return '../_assets/voice/7.mp3';
                    case '8':
                        return '../_assets/voice/8.mp3';
                    case '9':
                        return '../_assets/voice/9.mp3';
                    case '0':
                        return '../_assets/voice/0.mp3';
                    case '.':
                        // Jangan kembalikan file suara untuk titik
                        return '';
                    default:
                        return '';
                }
            }

            function getSoundDuration(character) {
                // Mendapatkan durasi karakter tertentu
                // Sesuaikan dengan durasi karakter suara Anda
                switch (character) {
                    case 'T':
                        return 800; // contoh durasi karakter "T" dalam milidetik
                    case 'C':
                        return 800;
                    case 'S':
                        return 800;
                    case '1':
                        return 800;
                    case '2':
                        return 800;
                    case '3':
                        return 800;
                    case '4':
                        return 800;
                    case '5':
                        return 800;
                    case '6':
                        return 800;
                    case '7':
                        return 800;
                    case '8':
                        return 800;
                    case '9':
                        return 800;
                    case '0':
                        return 800;
                    default:
                        return 0;
                }
            }

            function playSound(soundFile, callback) {
                // Setel file suara pada elemen audio
                audioPanggil.src = soundFile;

                // Memainkan suara
                audioPanggil.play();

                // Tunggu sebelum memanggil callback (jika ada)
                if (callback) {
                    // Sesuaikan durasi timeout dengan durasi suara yang sedang diputar
                    // Jika suara adalah start.mp3, atur timeout 4 detik
                    // Jika suara adalah loket.mp3, atur timeout 2 detik
                    var timeoutDuration = soundFile.includes('start.mp3') ? 4200 : 1800;
                    setTimeout(callback, timeoutDuration);
                }
            }
        </script>
<!-- Footer -->
<?php
include '../footer.php';
// Tutup koneksi
mysqli_close($conn);