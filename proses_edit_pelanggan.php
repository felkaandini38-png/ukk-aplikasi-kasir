
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['update'])) {

    $id = $_POST['id_pelanggan'];
    $nama = trim($_POST['nama_pelanggan']);
    $alamat = trim($_POST['alamat']);
    $telepon = trim($_POST['nomor_telp']);

    if (!ctype_digit($id) ||
        $nama == "" ||
        $alamat == "" ||
        $telepon == "") {

        echo "<script>
            alert('Data pelanggan tidak valid!');
            window.location='pelanggan.php';
        </script>";
        exit;
    }

    $query = mysqli_prepare($conn,
        "UPDATE pelanggan
         SET nama_pelanggan = ?,
             alamat = ?,
             nomor_telp = ?
         WHERE id_pelanggan = ?"
    );

    mysqli_stmt_bind_param(
        $query,
        "sssi",
        $nama,
        $alamat,
        $telepon,
        $id
    );

    if (mysqli_stmt_execute($query)) {

        echo "<script>
            alert('Data pelanggan berhasil diperbarui!');
            window.location='pelanggan.php';
        </script>";

    } else {

        echo "<script>
            alert('Data pelanggan gagal diperbarui!');
            window.location='pelanggan.php';
        </script>";
    }

    mysqli_stmt_close($query);

} else {
    header("Location: pelanggan.php");
    exit;
}
?>
