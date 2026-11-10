
<?php
session_start();

if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'petugas') {

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Petugas</title>
</head>
<body>

    <h1>DASHBOARD PETUGAS</h1>

    <h3>
        Selamat Datang,
        <?php echo htmlspecialchars($_SESSION['nama']); ?>
    </h3>

    <hr>

    <h2>MENU PETUGAS</h2>

    <ul>
        <li><a href="penjualan.php">Transaksi Penjualan</a></li>
        <li><a href="stok.php">Lihat Stok Barang</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

</body>
</html>
