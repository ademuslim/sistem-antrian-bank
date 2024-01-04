<?php
include '../header.php';

$userId = $_SESSION['user_id']; //digunakan jika role yang login adalah karyawan
$userRole = $_SESSION['user_role'];
?>

    <main>
        <section class="data-section">
            <div class="data-info">
                <h2 class="data-title">Data Riwayat Antrian Saat Ini</h2>
                <!-- Formulir untuk filter waktu -->

                <form class="filter-date" method="post" action="">
                    <label for="start_date">Mulai Tanggal:</label>
                    <input type="date" name="start_date" required>

                    <label for="end_date">Sampai Tanggal:</label>
                    <input type="date" name="end_date" required>

                    <input type="submit" name="filter" value="Filter">
                </form>
            </div>

            <div class="data-table">
                <table>
                    <thead>
                        <tr class="flex-row">
                            <th class="first-th">No.</th>
                            <th>Kode Antrian</th>
                            <th>Nasabah</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Status</th>
                            <th class="last-th">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Ambil input dari formulir (jika ada)
                    if (isset($_POST['filter'])) {
                        $start_date = $_POST['start_date'];
                        $end_date = $_POST['end_date'];
                    } else {
                        // Jika tidak ada input, atur default ke hari ini
                        $start_date = date('Y-m-d');
                        $end_date = date('Y-m-d');
                    }

                    $no = 1;
                    
                    // Jalankan query berdasarkan role pengguna
                    if ($userRole === 'admin') {
                        $query =    "SELECT antrian.*, nasabah.nama AS nama_nasabah
                                    FROM antrian
                                    INNER JOIN nasabah ON antrian.id_nasabah = nasabah.id_nasabah
                                    WHERE status_antrian IN ('batal', 'selesai')
                                    AND tanggal BETWEEN '$start_date' AND '$end_date'
                                    ORDER BY tanggal ASC";
                    } elseif ($userRole === 'karyawan') {
                        $query =    "SELECT antrian.*, nasabah.nama AS nama_nasabah
                                    FROM antrian
                                    INNER JOIN nasabah ON antrian.id_nasabah = nasabah.id_nasabah
                                    INNER JOIN loket ON antrian.id_loket = loket.id_loket
                                    WHERE status_antrian IN ('batal', 'selesai')
                                    AND tanggal BETWEEN '$start_date' AND '$end_date'
                                    AND loket.id_karyawan = '$userId'
                                    ORDER BY tanggal ASC";
                    }

                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    if (mysqli_num_rows($result) > 0) {
                        while ($data = mysqli_fetch_assoc(($result))) { ?>
                        <tr class="flex-row">
                            <td class="first-td"><?= $no++ ?></td>
                            <td><?= strtoupper($data['kode_antrian']) ?></td>
                            <td><?= ucwords($data['nama_nasabah']) ?></td>
                            <td><?= $data['waktu_mulai'] ?></td>
                            <td><?= $data['waktu_selesai'] ?></td>
                            <td>
                                <!-- memberi style sesuai status antrian -->
                                <span class="<?= getStatusClass($data['status_antrian']) ?>"><?= strtoupper($data['status_antrian']) ?></span>
                            </td>
                            <td class="last-td">
                                <div class="btn-group">
                                    <button class="btn" onclick="openModalDetailNasabah(<?= $data['id_nasabah'] ?>)">
                                        <span class="material-symbols-outlined">open_in_new</span>
                                    </button>
                                    <a href="del.php?id=<?= $data['id_antrian'] ?>" onclick="return confirm('Yakin akan menghapus data?')" class="secondary-btn" role="button" aria-label="Hapus data"><span class="material-symbols-outlined">delete</span></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                        }
                    } else {
                        echo "<tr><td colspan=\"7\" align=\"center\">Data tidak ditemukan.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

<!-- Modal Detail Nasabah -->
<div id="modalDetailNasabah">
    <div class="modal-content">
        <span class="close" onclick="closeModalDetailNasabah()">&times;</span>
        <h3>Detail Nasabah</h3>
        <table>
            <tr>
                <td>NIK:</td>
                <td id="detailNik"></td>
            </tr>
            <tr>
                <td>Nama:</td>
                <td id="detailNama"></td>
            </tr>
            <tr>
                <td>Jenis Kelamin:</td>
                <td id="detailJeKel"></td>
            </tr>
            <tr>
                <td>Tanggal Lahir:</td>
                <td id="detailTanggalLahir"></td>
            </tr>
            <tr>
                <td>Alamat:</td>
                <td id="detailAlamat"></td>
            </tr>
            <tr>
                <td>No. Handphone:</td>
                <td id="detailNoHp"></td>
            </tr>
            <!-- Tambahkan baris-baris tabel untuk data lainnya sesuai kebutuhan -->
        </table>
    </div>
</div>

<!-- JavaScript untuk modal -->
<script>
    function openModalDetailNasabah(idNasabah) {
        // Mengambil data Nasabah berdasarkan ID Nasabah
        fetch(`../data_nasabah/get_nasabah.php?id=${idNasabah}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('detailNik').innerHTML = data.nik;
                document.getElementById('detailNama').innerHTML = capitalizeWords(data.nama);
                document.getElementById('detailJeKel').innerHTML = capitalizeWords(data.jenis_kelamin);
                document.getElementById('detailTanggalLahir').innerHTML = data.tanggal_lahir;
                document.getElementById('detailAlamat').innerHTML = capitalizeWords(data.alamat);
                document.getElementById('detailNoHp').innerHTML = data.no_hp;
                // Tambahan: Tampilkan data lainnya sesuai kebutuhan
                document.getElementById('modalDetailNasabah').style.display = 'flex';
            })
            .catch(error => console.error('Error:', error));
    }

    function capitalizeWords(str) {
        // Mengonversi huruf pertama setiap kata menjadi kapital
        return str.replace(/\b\w/g, function (match) {
            return match.toUpperCase();
        });
    }

    function closeModalDetailNasabah() {
        document.getElementById('modalDetailNasabah').style.display = 'none';
    }

    // Menutup modal jika di-klik di luar modal
    window.onclick = function(event) {
        var modal = document.getElementById('modalDetailNasabah');
        if (event.target == modal) {
            closeModalDetailNasabah();
        }
    }
</script>

<!-- footer -->
<?php
include '../footer.php';