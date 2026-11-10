
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) ||
    !in_array($_SESSION['status'], ['admin', 'petugas'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: transaksi.php");
    exit;
}

$id = (int) $_GET['id'];

// Mengambil data penjualan dan pelanggan
$query = mysqli_prepare($conn,
    "SELECT penjualan.*, pelanggan.nama_pelanggan,
            pelanggan.alamat, pelanggan.nomor_telp
     FROM penjualan
     JOIN pelanggan
     ON penjualan.id_pelanggan = pelanggan.id_pelanggan
     WHERE penjualan.id_penjualan = ?"
);

mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Transaksi tidak ditemukan!");
}

// Mengambil detail barang
$detail = mysqli_prepare($conn,
    "SELECT detail_penjualan.*, produk.nama_produk,
            produk.harga
     FROM detail_penjualan
     JOIN produk
     ON detail_penjualan.id_produk = produk.id_produk
     WHERE detail_penjualan.id_penjualan = ?"
);

mysqli_stmt_bind_param($detail, "i", $id);
mysqli_stmt_execute($detail);

$hasil_detail = mysqli_stmt_get_result($detail);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Transaksi</title>

    <style>
        body {
            font-family: Arial;
            background: #f1f5f9;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 25px;
            max-width: 800px;
            margin: auto;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #2563eb;
            color: white;
        }

        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }

        @media print {
            body {
                background: white;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h2 style="text-align:center">
        STRUK PENJUALAN
    </h2>

    <hr>

    <p>
        <b>No Transaksi:</b>
        <?= $data['id_penjualan']; ?>
    </p>

    <p>
        <b>Tanggal:</b>
        <?= $data['tanggal_penjualan']; ?>
    </p>

    <p>
        <b>Nama Pelanggan:</b>
        <?= htmlspecialchars($data['nama_pelanggan']); ?>
    </p>

    <p>
        <b>Alamat:</b>
        <?= htmlspecialchars($data['alamat']); ?>
    </p>

    <p>
        <b>No. Telepon:</b>
        <?= htmlspecialchars($data['nomor_telp']); ?>
    </p>

    <hr>

    <table>

        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>

        <?php
        $no = 1;

        while ($d = mysqli_fetch_assoc($hasil_detail)) {
        ?>

        <tr>
            <td><?= $no++; ?></td>

            <td>
                <?= htmlspecialchars($d['nama_produk']); ?>
            </td>

            <td>
                Rp <?= number_format($d['harga'], 0, ',', '.'); ?>
            </td>

            <td><?= $d['jumlah_produk']; ?></td>

            <td>
                Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?>
            </td>
        </tr>

        <?php } ?>

    </table>

    <div class="total">
        Total Belanja:
        Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?>
    </div>

    <br>

    <div class="no-print">
        <button onclick="window.print()">
            Cetak Struk
        </button>

        <a href="transaksi.php">Transaksi Baru</a>
    </div>

</div>

</body>
</html>
