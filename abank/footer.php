<!-- footer -->
        <footer>
            <p>&copy; 2024 Sistem Antrian Layanan Bank | TI.20.C.RPL-4</p>
        </footer>
    </div>
<script>
    // Setelah semua sumber daya dimuat (termasuk gambar), hapus class "fade-out" dan tambahkan class "fade-in"
    window.addEventListener("load", function() {
        document.body.classList.remove("fade-out");
        document.body.classList.add("fade-in");
    });

    // Menampilkan nama file setelah dipilih
    document.getElementById("foto").addEventListener("change", function () {
        var fileInfo = document.getElementById("fileInfo");
        fileInfo.textContent = this.files[0].name;
    });

</script>  
</body>
</html>
