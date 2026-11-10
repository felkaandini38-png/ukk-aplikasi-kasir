
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
    <title>Tambah Barang</title>

    <style>
        body {
            font-family: Arial;
            background: #f1f5f9;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>FORM TAMBAH BARANG</h2>

    <form action="proses_tambah.php" method="POST">

        <label>Nama Barang</label>
        <input type="text" name="nama_produk"
               maxlength="100" required>

        <label>Harga Barang</label>
        <input type="number" name="harga"
               min="0" step="1" required>

        <label>Stok Barang</label>
        <input type="number" name="stok"
               min="0" step="1" required>

        <button type="submit" name="simpan">
            SIMPAN BARANG
        </button>

    </form>

    <br>

    <a href="produk.php">Kembali ke Data Barang</a>

</div>

</body>
</html>
