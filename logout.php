
<?php
session_start();

// Menghapus semua session
$_SESSION = array();
session_destroy();

// Kembali ke halaman login
header("Location: login.php");
exit;
?>