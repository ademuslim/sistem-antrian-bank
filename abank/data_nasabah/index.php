<?php
include '../header.php';
?>
    <main>
        <section class="data-section">
            <div class="data-info">
                <h2 class="data-title">Data Nasabah</h2>
                <button class="btn" onclick="window.location.href='add.php'"><span class="material-symbols-outlined">add</span>Tambah Nasabah</button>
            </div>
            <div class="data-table">
                <table>
                    <thead>
                        <tr class="flex-row">
                            <th class="first-th">No.</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>No. Handphone</th>
                            <th class="last-th">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $result = mysqli_query($conn, "SELECT * FROM nasabah") or die(mysqli_error($conn));
                    if (mysqli_num_rows($result) > 0) {
                        while ($data = mysqli_fetch_assoc(($result))) { ?>
                        <tr class="flex-row">
                            <td class="first-td"><?= $no++ ?></td>
                            <td><?= ucwords($data['nama']) ?></td>
                            <td><?= ucfirst($data['jenis_kelamin']) ?></td>
                            <td><?= $data['no_hp'] ?></td>
                            <td class="last-td">
                                <div class="btn-group">
                                    <button class="btn" onclick="openModalDetailNasabah(<?= $data['id_nasabah'] ?>)">
                                        <span class="material-symbols-outlined">open_in_new</span>
                                    </button>
                                    <a href="del.php?id=<?= $data['id_nasabah'] ?>" onclick="return confirm('Yakin akan menghapus data?')" class="secondary-btn" role="button" aria-label="Hapus data"><span class="material-symbols-outlined">delete</span></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                        }
                    } else {
                        echo "<tr><td colspan=\"5\" align=\"center\">Data tidak ditemukan.</td></tr>";
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
        fetch(`get_nasabah.php?id=${idNasabah}`)
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