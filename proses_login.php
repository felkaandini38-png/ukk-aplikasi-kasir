
<?php
session_start();
include "koneksi.php";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek username dan password
    $query = mysqli_prepare($conn,
        "SELECT * FROM user
         WHERE username = ? AND password = ?"
    );

    mysqli_stmt_bind_param(
        $query, "ss", $username, $password
    );

    mysqli_stmt_execute($query);

    $result = mysqli_stmt_get_result($query);

    if (mysqli_num_rows($result) > 0) {

        $data = mysqli_fetch_assoc($result);

        // Membuat session
        session_regenerate_id(true);

        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['status'] = $data['status'];

        // Mengarahkan berdasarkan status
        if ($data['status'] == 'admin') {

            header("Location: dashboard_admin.php");
            exit;

        } elseif ($data['status'] == 'petugas') {

            header("Location: dashboard_petugas.php");
            exit;

        } else {

            session_destroy();

            echo "<script>
                alert('Status pengguna tidak valid!');
                window.location='login.php';
            </script>";
        }

    } else {

        echo "<script>
            alert('Username atau Password Salah!');
            window.location='login.php';
        </script>";
    }

    mysqli_stmt_close($query);

} else {
    header("Location: login.php");
    exit;
}
?>
