
<?php
session_start();
include "koneksi.php";

// Admin dan petugas boleh melihat stok
if (!isset($_SESSION['id_user']) ||
    !in_array($_SESSION['status'], ['admin', 'petugas'])) {
    header("Location: login.php");
    exit;
}

// Mengambil seluruh data produk
$query = mysqli_query($conn,
    "SELECT * FROM produk ORDER BY nama_produk ASC"
);

// Menghitung jumlah produk dan total stok
$ringkasan = mysqli_query($conn,
    "SELECT COUNT(*) AS jumlah_produk,
            SUM(stok) AS total_stok
     FROM produk"
);

$hasil = mysqli_fetch_assoc($ringkasan);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stok Barang</title>

    <style>
        body {
            font-family: Arial;
            background: #f1f5f9;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        .info {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }

        .card {
            background: #dbeafe;
            padding: 20px;
            border-radius: 10px;
            flex: 1;
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

        .aman {
            color: green;
            font-weight: bold;
        }

        .sedikit {
            color: orange;
            font-weight: bold;
        }

        .habis {
            color: red;
            font-weight: bold;
        }

        .tambah {
            display: inline-block;
            padding: 10px;
            background: green;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>DATA STOK BARANG</h2>

    <a href="dashboard_admin.php">Kembali ke Dashboard</a>

    <div class="info">

        <div class="card">
            <h3>Jumlah Produk</h3>
            <h2><?= $hasil['jumlah_produk']; ?></h2>
        </div>

        <div class="card">
            <h3>Total Stok Barang</h3>
            <h2><?= $hasil['total_stok'] ?? 0; ?></h2>
        </div>

    </div>

    <?php if ($_SESSION['status'] == 'admin') { ?>
        <a href="tambah_stok.php" class="tambah">
            + Tambah Stok Barang
        </a>
    <?php } ?>

    <table>

        <tr>
            <th>No</th>
            <th>ID Produk</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Status Stok</th>
        </tr>

        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {

            if ($data['stok'] == 0) {
                $status = "Habis";
                $class = "habis";
            } elseif ($data['stok'] <= 5) {
                $status = "Hampir Habis";
                $class = "sedikit";
            } else {
                $status = "Tersedia";
                $class = "aman";
            }
        ?>

        <tr>

            <td><?= $no++; ?></td>

            <td><?= $data['id_produk']; ?></td>

            <td>
                <?= htmlspecialchars($data['nama_produk']); ?>
            </td>

            <td>
                Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
            </td>

            <td><?= $data['stok']; ?></td>

            <td class="<?= $class ?>">
                <?= $status; ?>
            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>
