<?php
include '../header.php';
?>
    <main>
        <section class="form-section">
            <div class="form-info">
                <h2 class="form-title">Tambah Admin</h2>
                <a href="index.php"><span class="material-symbols-outlined">arrow_back</span>KEMBALI</a>
            </div>
            <form action="proses.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" id="nik" name="nik" placeholder="NIK" required>

                    <label for="nama">Nama lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Nama lengkap" required>
                </div>

                <div class="form-group">
                    <label for="no_hp">Nomor handphone</label>
                    <input type="text" id="no_hp" name="no_hp" placeholder="Contoh: 012345678901" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>

                    <label for="foto">Foto</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>
                    <span class="file-info" id="fileInfo">Pilih foto...</span>

                    <button class="success-btn mt" type="submit" name="add">Simpan</button>
                </div>
            </form>
        </section>
    </main>

<?php
include '../footer.php';