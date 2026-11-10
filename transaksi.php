
<?php
session_start();
include "koneksi.php";

// Hak akses admin dan petugas
if (!isset($_SESSION['id_user']) ||
    !in_array($_SESSION['status'], ['admin', 'petugas'])) {
    header("Location: login.php");
    exit;
}

// Mengambil data pelanggan
$pelanggan = mysqli_query($conn,
    "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC"
);

// Mengambil data produk
$produk = mysqli_query($conn,
    "SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk ASC"
);

$daftar_produk = [];
while ($p = mysqli_fetch_assoc($produk)) {
    $daftar_produk[] = $p;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Penjualan</title>

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
            max-width: 950px;
            margin: auto;
        }

        input, select {
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background: #2563eb;
            color: white;
        }

        button {
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            color: white;
        }

        .tambah {
            background: green;
        }

        .hapus {
            background: red;
        }

        .simpan {
            background: #2563eb;
            width: 100%;
            margin-top: 20px;
        }

        .total {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>FORM TRANSAKSI PENJUALAN</h2>

    <a href="dashboard_admin.php">Kembali ke Dashboard</a>

    <form action="proses_transaksi.php" method="POST">

        <label>Pilih Pelanggan</label>

        <select name="id_pelanggan" required>
            <option value="">-- Pilih Pelanggan --</option>

            <?php while ($p = mysqli_fetch_assoc($pelanggan)) { ?>
                <option value="<?= $p['id_pelanggan']; ?>">
                    <?= htmlspecialchars($p['nama_pelanggan']); ?>
                </option>
            <?php } ?>

        </select>

        <h3>Daftar Barang</h3>

        <button type="button" class="tambah" onclick="tambahBaris()">
            + Tambah Barang
        </button>

        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="daftarBarang"></tbody>
        </table>

        <div class="total">
            Total: Rp <span id="total">0</span>
        </div>

        <button type="submit" name="simpan" class="simpan">
            SIMPAN TRANSAKSI
        </button>

    </form>

</div>

<template id="templateBarang">
    <tr>
        <td>
            <select name="id_produk[]" class="produk" required>
                <option value="">-- Pilih Produk --</option>

                <?php foreach ($daftar_produk as $p) { ?>
                    <option
                        value="<?= $p['id_produk']; ?>"
                        data-harga="<?= $p['harga']; ?>">
                        <?= htmlspecialchars($p['nama_produk']); ?>
                        (Stok: <?= $p['stok']; ?>)
                    </option>
                <?php } ?>

            </select>
        </td>

        <td class="harga">Rp 0</td>

        <td>
            <input type="number" name="jumlah_produk[]"
                   class="jumlah" min="1" value="1" required>
        </td>

        <td class="subtotal">Rp 0</td>

        <td>
            <button type="button" class="hapus"
                    onclick="hapusBaris(this)">
                Hapus
            </button>
        </td>
    </tr>
</template>

<script>
const daftarBarang = document.getElementById('daftarBarang');

function formatRupiah(angka) {
    return Number(angka).toLocaleString('id-ID');
}

function tambahBaris() {
    const template = document.getElementById('templateBarang');
    const baris = template.content.cloneNode(true);

    const select = baris.querySelector('.produk');
    const jumlah = baris.querySelector('.jumlah');

    select.addEventListener('change', hitungTotal);
    jumlah.addEventListener('input', hitungTotal);

    daftarBarang.appendChild(baris);
    hitungTotal();
}

function hapusBaris(tombol) {
    tombol.closest('tr').remove();
    hitungTotal();
}

function hitungTotal() {
    let total = 0;

    document.querySelectorAll('#daftarBarang tr').forEach(row => {
        const select = row.querySelector('.produk');
        const jumlah = Number(row.querySelector('.jumlah').value) || 0;

        const option = select.options[select.selectedIndex];
        const harga = Number(option?.dataset.harga || 0);

        const subtotal = harga * jumlah;

        row.querySelector('.harga').textContent =
            'Rp ' + formatRupiah(harga);

        row.querySelector('.subtotal').textContent =
            'Rp ' + formatRupiah(subtotal);

        total += subtotal;
    });

    document.getElementById('total').textContent =
        formatRupiah(total);
}

// Membuat satu baris barang saat halaman dibuka
tambahBaris();
</script>

</body>
</html>
