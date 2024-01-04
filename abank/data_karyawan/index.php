<?php
include '../header.php';
?>
    <main>
        <section class="data-section">
            <div class="data-info">
                <h2 class="data-title">Data Karyawan</h2>
                <button class="btn" onclick="window.location.href='add.php'"><span class="material-symbols-outlined">add</span>Tambah Karyawan</button>
            </div>
            <div class="data-table">
                <table>
                    <thead>
                        <tr class="flex-row">
                            <th class="first-th">No.</th>
                            <th>Nama</th>
                            <th>Bagian</th>
                            <th class="last-th">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $result = mysqli_query($conn, "SELECT * FROM karyawan") or die(mysqli_error($conn));
                    if (mysqli_num_rows($result) > 0) {
                        while ($data = mysqli_fetch_assoc(($result))) { ?>
                        <tr class="flex-row">
                            <td class="first-td"><?= $no++ ?></td>
                            <td><?= ucwords($data['nama']) ?></td>
                            <td><?= ucwords($data['bagian']) ?></td>
                            <td class="last-td">
                                <div class="btn-group">
                                    <button class="btn" onclick="openModalDetailKaryawan(<?= $data['id_karyawan'] ?>)">
                                        <span class="material-symbols-outlined">open_in_new</span>
                                    </button>
                                    <a href="edit.php?id=<?= $data['id_karyawan'] ?>" class="success-btn"><span class="material-symbols-outlined">edit</span></a>
                                    <a href="del.php?id=<?= $data['id_karyawan'] ?>" onclick="return confirm('Yakin akan menghapus data?')" class="secondary-btn" role="button" aria-label="Hapus data"><span class="material-symbols-outlined">delete</span></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                        }
                    } else {
                        echo "<tr><td colspan=\"4\" align=\"center\">Data tidak ditemukan.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

<!-- Modal Detail Karyawan -->
<div id="modalDetailKaryawan">
    <div class="modal-content">
        <span class="close" onclick="closeModalDetailKaryawan()">&times;</span>
        <h3>Detail Karyawan</h3>
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
                <td>Bagian:</td>
                <td id="detailBagian"></td>
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
    function openModalDetailKaryawan(idKaryawan) {
        // Mengambil data karyawan berdasarkan ID karyawan
        fetch(`get_karyawan.php?id=${idKaryawan}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('detailNik').innerHTML = data.nik;
                document.getElementById('detailNama').innerHTML = capitalizeWords(data.nama);
                document.getElementById('detailBagian').innerHTML = capitalizeWords(data.bagian);
                document.getElementById('detailNoHp').innerHTML = data.no_hp;
                // Tambahan: Tampilkan data lainnya sesuai kebutuhan
                document.getElementById('modalDetailKaryawan').style.display = 'flex';
            })
            .catch(error => console.error('Error:', error));
    }

    function capitalizeWords(str) {
        // Mengonversi huruf pertama setiap kata menjadi kapital
        return str.replace(/\b\w/g, function (match) {
            return match.toUpperCase();
        });
    }

    function closeModalDetailKaryawan() {
        document.getElementById('modalDetailKaryawan').style.display = 'none';
    }

    // Menutup modal jika di-klik di luar modal
    window.onclick = function(event) {
        var modal = document.getElementById('modalDetailKaryawan');
        if (event.target == modal) {
            closeModalDetailKaryawan();
        }
    }
</script>

<?php
include '../footer.php';