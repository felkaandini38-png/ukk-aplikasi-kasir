
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['update'])) {

    $id = $_POST['id_produk'];
    $nama = trim($_POST['nama_produk']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Validasi
    if (!ctype_digit($id) ||
        $nama == "" ||
        !ctype_digit($harga) ||
        !ctype_digit($stok)) {

        echo "<script>
            alert('Data tidak valid!');
            window.location='produk.php';
        </script>";
        exit;
    }

    // Update data barang
    $query = mysqli_prepare($conn,
        "UPDATE produk
         SET nama_produk = ?, harga = ?, stok = ?
         WHERE id_produk = ?"
    );

    mysqli_stmt_bind_param(
        $query,
        "siii",
        $nama,
        $harga,
        $stok,
        $id
    );

    if (mysqli_stmt_execute($query)) {

        echo "<script>
            alert('Data barang berhasil diperbarui!');
            window.location='produk.php';
        </script>";

    } else {

        echo "<script>
            alert('Data barang gagal diperbarui!');
            window.location='produk.php';
        </script>";
    }

    mysqli_stmt_close($query);
}
?>
