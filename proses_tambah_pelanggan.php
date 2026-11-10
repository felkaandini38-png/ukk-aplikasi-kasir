
<?php
session_start();
include "koneksi.php";

// Cek hak akses admin
if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama_pelanggan']);
    $alamat = trim($_POST['alamat']);
    $telepon = trim($_POST['nomor_telp']);

    if ($nama == "" || $alamat == "" || $telepon == "") {
        echo "<script>
            alert('Semua data harus diisi!');
            window.location='tambah_pelanggan.php';
        </script>";
        exit;
    }

    $query = mysqli_prepare($conn,
        "INSERT INTO pelanggan
         (nama_pelanggan, alamat, nomor_telp)
         VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $query,
        "sss",
        $nama,
        $alamat,
        $telepon
    );

    if (mysqli_stmt_execute($query)) {

        echo "<script>
            alert('Data pelanggan berhasil ditambahkan!');
            window.location='pelanggan.php';
        </script>";

    } else {

        echo "<script>
            alert('Data pelanggan gagal ditambahkan!');
            window.location='tambah_pelanggan.php';
        </script>";
    }

    mysqli_stmt_close($query);

} else {
    header("Location: pelanggan.php");
    exit;
}
?>
