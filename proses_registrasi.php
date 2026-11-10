
<?php
session_start();
include "koneksi.php";

// Memastikan hanya admin yang bisa registrasi
if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['registrasi'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $nama = trim($_POST['nama']);
    $status = $_POST['status'];

    // Validasi input
    if ($username == "" || $password == "" || $nama == "") {
        echo "<script>
            alert('Semua data harus diisi!');
            window.location='registrasi.php';
        </script>";
        exit;
    }

    if (!in_array($status, ['admin', 'petugas'])) {
        echo "<script>
            alert('Status tidak valid!');
            window.location='registrasi.php';
        </script>";
        exit;
    }

    // Cek username apakah sudah digunakan
    $cek = mysqli_prepare(
        $conn,
        "SELECT id_user FROM user WHERE username = ?"
    );

    mysqli_stmt_bind_param($cek, "s", $username);
    mysqli_stmt_execute($cek);

    $hasil = mysqli_stmt_get_result($cek);

    if (mysqli_num_rows($hasil) > 0) {

        echo "<script>
            alert('Username sudah digunakan!');
            window.location='registrasi.php';
        </script>";
        exit;
    }

    // Menyimpan data pengguna
    $query = mysqli_prepare(
        $conn,
        "INSERT INTO user
        (username, password, nama, status)
        VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $query,
        "ssss",
        $username,
        $password,
        $nama,
        $status
    );

    if (mysqli_stmt_execute($query)) {

        echo "<script>
            alert('Registrasi berhasil disimpan!');
            window.location='registrasi.php';
        </script>";

    } else {

        echo "<script>
            alert('Registrasi gagal disimpan!');
            window.location='registrasi.php';
        </script>";
    }

    mysqli_stmt_close($cek);
    mysqli_stmt_close($query);

} else {

    header("Location: registrasi.php");
    exit;
}
?>
