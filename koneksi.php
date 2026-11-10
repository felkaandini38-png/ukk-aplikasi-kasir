
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kasir";

// Membuat koneksi database
$conn = mysqli_connect($host, $user, $pass, $db);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

echo "Koneksi database berhasil!";
?>