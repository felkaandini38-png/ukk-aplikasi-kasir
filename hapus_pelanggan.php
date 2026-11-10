
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['id_pelanggan']) &&
    ctype_digit($_POST['id_pelanggan'])) {

    $id = (int) $_POST['id_pelanggan'];

    $query = mysqli_prepare($conn,
        "DELETE FROM pelanggan
         WHERE id_pelanggan = ?"
    );

    mysqli_stmt_bind_param($query, "i", $id);

    if (mysqli_stmt_execute($query)) {

        echo "<script>
            alert('Data pelanggan berhasil dihapus!');
            window.location='pelanggan.php';
        </script>";

    } else {

        echo "<script>
            alert('Data pelanggan tidak dapat dihapus. Pelanggan mungkin sudah memiliki transaksi!');
            window.location='pelanggan.php';
        </script>";
    }

    mysqli_stmt_close($query);

} else {

    header("Location: pelanggan.php");
    exit;
}
?>
