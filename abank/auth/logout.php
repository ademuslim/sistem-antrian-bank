<?php
require_once "../_config.php";

// Unset semua session
session_unset();

// Redirect ke halaman login
echo "<script>window.location='" . base_url('auth/login.php') . "';</script>";
?>
