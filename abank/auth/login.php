<?php
require_once '../_config.php';
if (isset($_SESSION['user'])) {
    echo "<script>window.location='" . base_url() . "';</script>";
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Antrian Bank</title>
    <link rel="stylesheet" href="../_assets/css/login-style.css">
</head>
<body>
    <header>
        <div class="brand">Antrian<span>Bank</span></div>
        <div class="bank-info">
            <div class="bank-name">BANK XYZ</div>
            <div class="bank-address">Jl. Lorem, ipsum.</div>
        </div>
    </header>
    <main>
        <div class="tagline">
            <p>Antri Dengan <span>Nyaman</span></p>
            <p>Layanan Dengan Senyuman</p>
            <p>Pengalaman Antrian Bank Menarik Menanti Anda</p>
        </div>
        <div class="main-content">
            <div class="form-login">
                <form action="proses.php" method="post">
                    <h2>Login</h2>
                    <input type="text" name="no_hp" required placeholder="Nomor handphone">

                    <input type="password" name="password" required placeholder="Password">

                    <input type="submit" name="login" value="Login"></input>
                </form>

                <div class="help">
                    <p>Jika anda belum memiliki akun, silahkan Registrasi terlebih dahulu.</p>
                    <div class="button-help">
                        <button onclick="openModalRegistrasi()">Registrasi</button>
                        <button onclick="openModalBantuan()">Bantuan</button>
                    </div>
                </div>
            </div>

            <?php
            // Query untuk mendapatkan informasi loket dan banyaknya antrian dengan status menunggu
            $query_info_loket = "SELECT l.kode_loket, l.nama_loket, COUNT(a.id_antrian) as jumlah_antrian
            FROM loket l
            LEFT JOIN antrian a ON l.id_loket = a.id_loket AND a.status_antrian = 'menunggu'
            GROUP BY l.id_loket";
            $result_info_loket = mysqli_query($conn, $query_info_loket);
            ?>

            <div class="antrian-info">
            <?php while ($row_info_loket = mysqli_fetch_assoc($result_info_loket)) { ?>
                <div class="antrian-info-item">
                    <div class="number"><?php echo $row_info_loket['jumlah_antrian']; ?></div>
                    <div class="loket-name"><?php echo strtoupper($row_info_loket['nama_loket']); ?></div>
                </div>
            <?php } ?>
            </div>
        </div>
    </main>

    <div class="modalRegistrasi">
        <div class="modal-content">
            <div class="modal-info">
                <h3 class="modal-titel">Registrasi</h3>
                <span class="closeModalRegistrasi" onclick="closeModalRegistrasi">&times;</span>
            </div>
            <form class="registration-form" action="proses.php" method="post">
                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" id="nik" name="nik" placeholder="NIK" required>

                    <label for="nama">Nama lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Nama lengkap" required>

                    <label for="jenis_kelamin">Jenis kelamin</label>
                    <div class="form-group-radio">
                        <input type="radio" id="jenis_kelamin_pria" name="jenis_kelamin" value="laki-laki" required>
                        <label for="jenis_kelamin_pria">Laki-laki</label>

                        <input type="radio" id="jenis_kelamin_wanita" name="jenis_kelamin" value="perempuan" required>
                        <label for="jenis_kelamin_wanita">Perempuan</label>
                    </div>

                    <label for="tanggal_lahir">Tanggal lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" placeholder="Tanggal lahir" required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" placeholder="Alamat" required>

                    <label for="no_hp">Nomor handphone</label>
                    <input type="text" id="no_hp" name="no_hp" placeholder="Contoh: 012345678901" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>

                    <input type="submit" name="registrasi_nasabah" value="Daftar"></input>
                </div>
            </form>
        </div>
    </div>

    <div class="modalBantuan">
        <div class="modal-content">
            <div class="modal-info">
                <h3 class="modal-title">Bantuan</h3>
                <span class="closeModalBantuan" onclick="closeModalBantuan">&times;</span>
            </div>
            <div class="content-bantuan">
                <div class="content-bantuan-group">
                    <p>Jika belum meiliki akun Antrian Bank XYZ</p>
                    <p>Klik Registrasi > Login > Ambil Antrian</p>
                </div>
                <div class="content-bantuan-group">
                    <p>Nasabah melakukan registrasi:</p>
                    <p>Input NIK, nama lengkap, jenis kelamin, tanggal lahir, alamat, nomor handphone, dan password.</p>
                </div>
                <div class="content-bantuan-group">
                    <p>Nasabah melakukan login:</p>
                    <p>Input nomor handphone dan password yang terdaftar.</p>
                </div>
                <div class="content-bantuan-group">
                    <p>Nasabah mengambil antrian:</p>
                    <p>Pilih Jenis Antrian lalu klik tombol Ambil Antrian.</p>
                </div>
            </div>
        </div>
    </div>


    <script src="../_assets/js/login-scripts.js"></script>
</body>
</html>
<?php
}
?>