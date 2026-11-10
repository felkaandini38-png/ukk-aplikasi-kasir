
<?php
session_start();
include "koneksi.php";

// Cek login dan hak akses
if (!isset($_SESSION['id_user']) ||
    !in_array($_SESSION['status'], ['admin', 'petugas'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['simpan'])) {
    header("Location: transaksi.php");
    exit;
}

$id_pelanggan = $_POST['id_pelanggan'] ?? '';
$id_produk = $_POST['id_produk'] ?? [];
$jumlah_produk = $_POST['jumlah_produk'] ?? [];

// Validasi pelanggan
if (!ctype_digit((string)$id_pelanggan) ||
    !is_array($id_produk) ||
    !is_array($jumlah_produk) ||
    count($id_produk) == 0 ||
    count($id_produk) != count($jumlah_produk)) {

    die("Data transaksi tidak valid!");
}

mysqli_begin_transaction($conn);

try {

    // Memeriksa pelanggan
    $cek_pelanggan = mysqli_prepare(
        $conn,
        "SELECT id_pelanggan FROM pelanggan
         WHERE id_pelanggan = ?"
    );

    mysqli_stmt_bind_param(
        $cek_pelanggan, "i", $id_pelanggan
    );

    mysqli_stmt_execute($cek_pelanggan);

    $hasil_pelanggan = mysqli_stmt_get_result($cek_pelanggan);

    if (mysqli_num_rows($hasil_pelanggan) == 0) {
        throw new Exception("Pelanggan tidak ditemukan!");
    }

    // Membuat transaksi sementara
    $tanggal = date("Y-m-d H:i:s");
    $total_harga = 0;

    $simpan_penjualan = mysqli_prepare(
        $conn,
        "INSERT INTO penjualan
         (tanggal_penjualan, total_harga, id_pelanggan)
         VALUES (?, 0, ?)"
    );

    mysqli_stmt_bind_param(
        $simpan_penjualan, "si",
        $tanggal, $id_pelanggan
    );

    if (!mysqli_stmt_execute($simpan_penjualan)) {
        throw new Exception("Gagal menyimpan penjualan!");
    }

    $id_penjualan = mysqli_insert_id($conn);

    // Menyiapkan query produk
    $cek_produk = mysqli_prepare(
        $conn,
        "SELECT harga, stok FROM produk
         WHERE id_produk = ? FOR UPDATE"
    );

    // Menyiapkan query detail
    $simpan_detail = mysqli_prepare(
        $conn,
        "INSERT INTO detail_penjualan
         (id_penjualan, id_produk, jumlah_produk, subtotal)
         VALUES (?, ?, ?, ?)"
    );

    // Query mengurangi stok
    $update_stok = mysqli_prepare(
        $conn,
        "UPDATE produk
         SET stok = stok - ?
         WHERE id_produk = ?"
    );

    $ada_barang = false;

    // Proses setiap barang
    for ($i = 0; $i < count($id_produk); $i++) {

        $id = $id_produk[$i];
        $jumlah = $jumlah_produk[$i];

        // Baris kosong dilewati
        if ($id === '') {
            continue;
        }

        if (!ctype_digit((string)$id) ||
            !ctype_digit((string)$jumlah) ||
            (int)$id < 1 ||
            (int)$jumlah < 1) {
            throw new Exception("Data produk atau jumlah tidak valid!");
        }

        $id = (int)$id;
        $jumlah = (int)$jumlah;

        // Mengunci dan mengambil harga serta stok produk
        mysqli_stmt_bind_param($cek_produk, "i", $id);
        mysqli_stmt_execute($cek_produk);

        $hasil_produk = mysqli_stmt_get_result($cek_produk);
        $produk = mysqli_fetch_assoc($hasil_produk);

        if (!$produk) {
            throw new Exception("Produk tidak ditemukan!");
        }

        $harga = (float)$produk['harga'];
        $stok = (int)$produk['stok'];

        // Memeriksa stok
        if ($jumlah > $stok) {
            throw new Exception(
                "Stok produk ID $id tidak mencukupi!"
            );
        }

        // Menghitung subtotal
        $subtotal = $harga * $jumlah;
        $total_harga += $subtotal;

        // Menyimpan detail transaksi
        mysqli_stmt_bind_param(
            $simpan_detail,
            "iiid",
            $id_penjualan,
            $id,
            $jumlah,
            $subtotal
        );

        if (!mysqli_stmt_execute($simpan_detail)) {
            throw new Exception("Gagal menyimpan detail penjualan!");
        }

        // Mengurangi stok produk
        mysqli_stmt_bind_param(
            $update_stok, "ii", $jumlah, $id
        );

        if (!mysqli_stmt_execute($update_stok)) {
            throw new Exception("Gagal memperbarui stok!");
        }

        $ada_barang = true;
    }

    if (!$ada_barang) {
        throw new Exception("Pilih minimal satu produk!");
    }

    // Memperbarui total transaksi
    $update_total = mysqli_prepare(
        $conn,
        "UPDATE penjualan
         SET total_harga = ?
         WHERE id_penjualan = ?"
    );

    mysqli_stmt_bind_param(
        $update_total, "di",
        $total_harga, $id_penjualan
    );

    if (!mysqli_stmt_execute($update_total)) {
        throw new Exception("Gagal memperbarui total harga!");
    }

    // Jika semua berhasil, simpan transaksi
    mysqli_commit($conn);

    header("Location: detail_transaksi.php?id=" . $id_penjualan);
    exit;

} catch (Throwable $e) {

    // Jika gagal, batalkan semua perubahan
    mysqli_rollback($conn);

    echo "<script>
        alert(" . json_encode("Transaksi gagal: " . $e->getMessage()) . ");
        window.location='transaksi.php';
    </script>";
    exit;
}
?>
