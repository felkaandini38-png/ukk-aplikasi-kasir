
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Mengambil data produk
$query = mysqli_query($conn,
    "SELECT * FROM produk ORDER BY nama_produk ASC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Stok Barang</title>

    <style>
        body {
            font-family: Arial;
            background: #f1f5f9;
        }

        .container {
            width: 420px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 20px;
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

    <h2>TAMBAH STOK BARANG</h2>

    <form action="proses_tambah_stok.php" method="POST">

        <label>Pilih Barang</label>

        <select name="id_produk" required>

            <option value="">-- Pilih Barang --</option>

            <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                <option value="<?= $data['id_produk']; ?>">
                    <?= htmlspecialchars($data['nama_produk']); ?>
                    (Stok: <?= $data['stok']; ?>)
                </option>

            <?php } ?>

        </select>

        <label>Jumlah Stok Masuk</label>

        <input type="number"
               name="jumlah_stok"
               min="1"
               required
               placeholder="Masukkan jumlah stok">

        <button type="submit" name="simpan">
            SIMPAN STOK
        </button>

    </form>

    <br>

    <a href="stok.php">Kembali ke Data Stok</a>

</div>

</body>
</html>
