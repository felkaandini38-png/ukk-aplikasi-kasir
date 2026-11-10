
<?php
session_start();

if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>

    <h1>DASHBOARD ADMIN</h1>

    <h3>
        Selamat Datang,
        <?php echo htmlspecialchars($_SESSION['nama']); ?>
    </h3>

    <hr>

    <h2>MENU ADMIN</h2>

    <ul>
        <li><a href="registrasi.php">Registrasi Pengguna</a></li>
        <li><a href="produk.php">Data Produk</a></li>
        <li><a href="pelanggan.php">Data Pelanggan</a></li>
        <li><a href="transaksi.php">Data Penjualan</a></li>
        <li><a href="stok.php">Stok Barang</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

</body>
</html>
