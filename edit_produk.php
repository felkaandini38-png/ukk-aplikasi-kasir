
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: produk.php");
    exit;
}

$id = (int) $_GET['id'];

$query = mysqli_prepare($conn,
    "SELECT * FROM produk WHERE id_produk = ?"
);

mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: produk.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>

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
            background: orange;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>FORM EDIT BARANG</h2>

    <form action="proses_edit.php" method="POST">

        <input type="hidden" name="id_produk"
               value="<?= $data['id_produk']; ?>">

        <label>Nama Barang</label>
        <input type="text" name="nama_produk"
               value="<?= htmlspecialchars($data['nama_produk']); ?>"
               maxlength="100" required>

        <label>Harga Barang</label>
        <input type="number" name="harga"
               value="<?= $data['harga']; ?>"
               min="0" step="1" required>

        <label>Stok Barang</label>
        <input type="number" name="stok"
               value="<?= $data['stok']; ?>"
               min="0" step="1" required>

        <button type="submit" name="update">
            UPDATE BARANG
        </button>

    </form>

    <br>

    <a href="produk.php">Kembali</a>

</div>

</body>
</html>
