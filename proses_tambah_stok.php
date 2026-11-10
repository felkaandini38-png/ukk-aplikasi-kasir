
<?php
session_start();
include "koneksi.php";

// Hak akses admin
if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $id_produk = $_POST['id_produk'];
    $jumlah_stok = $_POST['jumlah_stok'];

    // Validasi input
    if (!ctype_digit($id_produk) ||
        !ctype_digit($jumlah_stok) ||
        (int)$id_produk < 1 ||
        (int)$jumlah_stok < 1) {

        echo "<script>
            alert('Data stok tidak valid!');
            window.location='tambah_stok.php';
        </script>";
        exit;
    }

    $id_produk = (int)$id_produk;
    $jumlah_stok = (int)$jumlah_stok;

    // Menambahkan stok ke database
    $query = mysqli_prepare($conn,
        "UPDATE produk
         SET stok = stok + ?
         WHERE id_produk = ?"
    );

    mysqli_stmt_bind_param(
        $query,
        "ii",
        $jumlah_stok,
        $id_produk
    );

    if (mysqli_stmt_execute($query) &&
        mysqli_stmt_affected_rows($query) > 0) {

        echo "<script>
            alert('Stok barang berhasil ditambahkan!');
            window.location='stok.php';
        </script>";

    } else {

        echo "<script>
            alert('Stok gagal ditambahkan atau produk tidak ditemukan!');
            window.location='tambah_stok.php';
        </script>";
    }

    mysqli_stmt_close($query);

} else {

    header("Location: stok.php");
    exit;
}
?>
