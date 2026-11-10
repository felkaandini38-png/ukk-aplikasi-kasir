
<?php
session_start();

if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Pengguna</title>

    <style>
        body {
            font-family: Arial;
            background: #f1f5f9;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            color: #2563eb;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
        }

        a {
            display: block;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>REGISTRASI PENGGUNA</h2>

    <form action="proses_registrasi.php" method="POST">

        <label>Username</label>
        <input type="text" name="username"
               placeholder="Masukkan Username"
               maxlength="50" required>

        <label>Password</label>
        <input type="password" name="password"
               placeholder="Masukkan Password"
               required>

        <label>Nama Lengkap</label>
        <input type="text" name="nama"
               placeholder="Masukkan Nama Lengkap"
               maxlength="50" required>

        <label>Status Pengguna</label>
        <select name="status" required>
            <option value="">-- Pilih Status --</option>
            <option value="admin">Admin</option>
            <option value="petugas">Petugas</option>
        </select>

        <button type="submit" name="registrasi">
            SIMPAN DATA
        </button>

    </form>

    <a href="dashboard_admin.php">Kembali ke Dashboard</a>

</div>

</body>
</html>
