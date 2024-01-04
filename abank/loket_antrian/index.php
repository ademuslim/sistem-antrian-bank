<?php
include '../header.php';
?>
    
    <main>
        <section class="data-section">
            <div class="data-info">
                <h2 class="data-title">Loket Antrian</h2>
                <button class="btn" onclick="window.location.href='add.php'"><span class="material-symbols-outlined">add</span>Tambah Loket</button>
            </div>
            <div class="data-table">
                <table>
                    <thead>
                        <tr class="flex-row">
                            <th class="first-th">No.</th>
                            <th class="fix-th">Kode</th>
                            <th>Nama Loket</th>
                            <th>Jenis Antrian</th>
                            <th>Karyawan</th>
                            <th class="fix-th">Status</th>
                            <th class="last-th">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $result = mysqli_query($conn, "SELECT * FROM loket INNER JOIN karyawan ON loket.id_karyawan = karyawan.id_karyawan") or die(mysqli_error($conn));
                    if (mysqli_num_rows($result) > 0) {
                        while ($data = mysqli_fetch_assoc(($result))) { ?>
                        <tr class="flex-row">
                            <td class="first-th"><?= $no++ ?></td>
                            <td class="fix-td"><?= strtoupper($data['kode_loket']) ?></td>
                            <td><?= ucwords($data['nama_loket']) ?></td>
                            <td><?= ucwords($data['bagian']) ?></td>
                            <td><?= ucwords($data['nama']) ?></td>
                            <td class="fix-td">
                                <!-- memberi style sesuai status antrian -->
                                <span class="<?= getStatusClass($data['status_loket']) ?>"><?= strtoupper($data['status_loket']) ?></span>
                            </td>
                            <td class="last-td">
                                <div class="btn-group">
                                    <a href="edit.php?id=<?= $data['id_loket'] ?>" class="success-btn"><span class="material-symbols-outlined">edit</span></a>
                                    <a href="del.php?id=<?= $data['id_loket'] ?>" onclick="return confirm('Yakin akan menghapus data?')" class="secondary-btn" role="button" aria-label="Hapus data"><span class="material-symbols-outlined">delete</span></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                        }
                    } else {
                        echo "<tr><td colspan=\"8\" align=\"center\">Data tidak ditemukan.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

<!-- footer -->
<?php
include '../footer.php';