
<?php
session_start();
include "koneksi.php";

// Cek hak akses
if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $nama_produk = trim($_POST['nama_produk']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Validasi data
    if ($nama_produk == "" ||
        !ctype_digit($harga) ||
        !ctype_digit($stok)) {

        echo "<script>
            alert('Data barang tidak valid!');
            window.location='tambah_produk.php';
        </script>";
        exit;
    }

    // Query simpan data
    $query = mysqli_prepare($conn,
        "INSERT INTO produk (nama_produk, harga, stok)
         VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $query,
        "sii",
        $nama_produk,
        $harga,
        $stok
    );

    if (mysqli_stmt_execute($query)) {

        echo "<script>
            alert('Data barang berhasil ditambahkan!');
            window.location='produk.php';
        </script>";

    } else {

        echo "<script>
            alert('Data barang gagal ditambahkan!');
            window.location='tambah_produk.php';
        </script>";
    }

    mysqli_stmt_close($query);
}
?>
