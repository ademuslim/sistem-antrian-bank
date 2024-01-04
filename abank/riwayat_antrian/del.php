<?php
require_once "../_config.php";

mysqli_query($conn, "DELETE FROM antrian WHERE id_antrian = '$_GET[id]'") or die(mysqli_error($conn));
echo "<script>window.location='index.php';</script>";