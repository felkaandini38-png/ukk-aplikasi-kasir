
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
    <title>Tambah Pelanggan</title>

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

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>FORM TAMBAH PELANGGAN</h2>

    <form action="proses_tambah_pelanggan.php" method="POST">

        <label>Nama Pelanggan</label>
        <input type="text"
               name="nama_pelanggan"
               maxlength="100"
               placeholder="Masukkan nama pelanggan"
               required>

        <label>Alamat</label>
        <textarea name="alamat"
                  rows="4"
                  placeholder="Masukkan alamat pelanggan"
                  required></textarea>

        <label>Nomor Telepon</label>
        <input type="tel"
               name="nomor_telp"
               maxlength="20"
               placeholder="Masukkan nomor telepon"
               required>

        <button type="submit" name="simpan">
            SIMPAN PELANGGAN
        </button>

    </form>

    <br>

    <a href="pelanggan.php">Kembali ke Data Pelanggan</a>

</div>

</body>
</html>
