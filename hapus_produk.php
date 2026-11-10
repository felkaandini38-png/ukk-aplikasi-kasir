
<?php
session_start();
include "koneksi.php";

// Cek hak akses admin
if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['id_produk']) &&
    ctype_digit($_POST['id_produk'])) {

    $id = (int) $_POST['id_produk'];

    $query = mysqli_prepare($conn,
        "DELETE FROM produk WHERE id_produk = ?"
    );

    mysqli_stmt_bind_param($query, "i", $id);

    if (mysqli_stmt_execute($query)) {

        echo "<script>
            alert('Data barang berhasil dihapus!');
            window.location='produk.php';
        </script>";

    } else {

        echo "<script>
            alert('Data barang gagal dihapus!');
            window.location='produk.php';
        </script>";
    }

    mysqli_stmt_close($query);

} else {

    header("Location: produk.php");
    exit;
}
?>
