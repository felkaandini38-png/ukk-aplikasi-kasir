
<?php
session_start();
include "koneksi.php";

// Cek hak akses admin
if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Mengambil data produk
$query = mysqli_query($conn,
    "SELECT * FROM produk ORDER BY id_produk DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>

    <style>
        body {
            font-family: Arial;
            background: #f1f5f9;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #2563eb;
            color: white;
        }

        .tambah {
            background: green;
            color: white;
            padding: 10px;
            text-decoration: none;
        }

        .edit {
            background: orange;
            color: white;
            padding: 6px;
            text-decoration: none;
        }

        .hapus {
            background: red;
            color: white;
            padding: 6px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>DATA BARANG</h2>

    <a href="dashboard_admin.php">Kembali</a>
    <br><br>

    <a href="tambah_produk.php" class="tambah">
        + Tambah Barang
    </a>

    <table>
        <tr>
            <th>No</th>
            <th>ID Produk</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {
        ?>

        <tr>
            <td><?= $no++; ?></td>
            <td><?= $data['id_produk']; ?></td>
            <td><?= htmlspecialchars($data['nama_produk']); ?></td>
            <td>Rp <?= number_format($data['harga'], 0, ',', '.'); ?></td>
            <td><?= $data['stok']; ?></td>

            <td>
                <a class="edit"
                   href="edit_produk.php?id=<?= $data['id_produk']; ?>">
                   Edit
                </a>

                <form action="hapus_produk.php"
                      method="POST"
                      style="display:inline;"
                      onsubmit="return confirm('Yakin ingin menghapus barang ini?')">

                    <input type="hidden" name="id_produk"
                           value="<?= $data['id_produk']; ?>">

                    <button type="submit" class="hapus">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>
