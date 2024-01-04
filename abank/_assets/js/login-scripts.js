// MODAL REGISTRASI
// Ambil elemen modal registrasi
var modalRegistrasi = document.querySelector(".modalRegistrasi");

// Ambil elemen tombol close pada modal registrasi
var closeBtnRegistrasi = document.querySelector(".closeModalRegistrasi");

// Fungsi untuk membuka modal registrasi
function openModalRegistrasi() {
    modalRegistrasi.style.display = "flex";
}

// Fungsi untuk menutup modal registrasi
function closeModalRegistrasi() {
    modalRegistrasi.style.display = "none";
}

// Tambahkan event listener untuk tombol close pada modal registrasi
closeBtnRegistrasi.addEventListener("click", closeModalRegistrasi);

// Fungsi untuk menangani klik di luar area modal registrasi
window.onclick = function (event) {
    if (event.target === modalRegistrasi) {
        closeModalRegistrasi();
    }
};

// Fungsi untuk menangani klik tombol Daftar
function registerUser() {
    // Implementasikan logika pendaftaran pengguna di sini
    // Misalnya, validasi formulir dan kirim data ke server
    // Setelah berhasil, Anda mungkin ingin menutup modal dengan memanggil closeModalRegistrasi()
    // closeModalRegistrasi();
}

// MODAL BANTUAN
// Ambil elemen modal registrasi
var modalBantuan = document.querySelector(".modalBantuan");

// Ambil elemen tombol close pada modal registrasi
var closeBtnBantuan = document.querySelector(".closeModalBantuan");

// Fungsi untuk membuka modal registrasi
function openModalBantuan() {
    modalBantuan.style.display = "flex";
}

// Fungsi untuk menutup modal registrasi
function closeModalBantuan() {
    modalBantuan.style.display = "none";
}

// Tambahkan event listener untuk tombol close pada modal registrasi
closeBtnBantuan.addEventListener("click", closeModalBantuan);

// Fungsi untuk menangani klik di luar area modal registrasi
window.onclick = function (event) {
    if (event.target === modalBantuan) {
        closeModalBantuan();
    }
};

