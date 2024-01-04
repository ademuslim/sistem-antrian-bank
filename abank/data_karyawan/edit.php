<?php
include '../header.php';
?>
    <main>
        <section class="form-section">
            <div class="form-info">
                <h2 class="form-title">Edit Data Karyawan</h2>
                <a href="index.php"><span class="material-symbols-outlined">arrow_back</span>KEMBALI</a>
            </div>

            <?php
            $id = @$_GET['id'];
            $sql_karyawan = mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan = '$id'") or die(mysqli_error($conn));
            $data = mysqli_fetch_array($sql_karyawan);

            // Query untuk mendapatkan opsi Role dari database
            $sql_role = mysqli_query($conn, "SELECT DISTINCT bagian FROM karyawan") or die(mysqli_error($conn));
            ?>

            <form action="proses.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="id" value="<?= $data['id_karyawan'] ?>">

                    <label for="nik">Nomor Induk Kependudukan</label>
                    <input type="text" id="nik" name="nik" value="<?= $data['nik'] ?>" required autofocus>

                    <label for="nama">Nama lengkap</label>
                    <input type="text" id="nama" name="nama" value="<?= $data['nama'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="bagian">Bagian</label>
                    <select id="bagian" name="bagian" required>
                        <option value="" disabled>-- Pilih Bagian --</option>
                        <?php
                        while ($bagian = mysqli_fetch_assoc($sql_role)) {
                            $selected = ($data['bagian'] == $bagian['bagian']) ? 'selected' : '';
                            echo '<option value="' . $bagian['bagian'] . '" ' . $selected . '>' . $bagian['bagian'] . '</option>';
                        }
                        ?>
                    </select>
                    <label for="no_hp">Nomor handphone</label>
                    <input type="text" id="no_hp" name="no_hp" value="<?= $data['no_hp'] ?>" required>

                    <!-- <label for="foto">Foto</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>
                    <span class="file-info" id="fileInfo">Pilih foto...</span> -->

                    <button class="success-btn mt" type="submit" name="edit">Simpan</button>
                </div>
            </form>
        </section>
    </main>

<!-- footer -->
<?php
include '../footer.php';