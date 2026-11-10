
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: pelanggan.php");
    exit;
}

$id = (int) $_GET['id'];

$query = mysqli_prepare($conn,
    "SELECT * FROM pelanggan WHERE id_pelanggan = ?"
);

mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: pelanggan.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pelanggan</title>

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
            background: orange;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>FORM EDIT PELANGGAN</h2>

    <form action="proses_edit_pelanggan.php" method="POST">

        <input type="hidden"
               name="id_pelanggan"
               value="<?= $data['id_pelanggan']; ?>">

        <label>Nama Pelanggan</label>
        <input type="text"
               name="nama_pelanggan"
               maxlength="100"
               value="<?= htmlspecialchars($data['nama_pelanggan']); ?>"
               required>

        <label>Alamat</label>
        <textarea name="alamat"
                  rows="4"
                  required><?= htmlspecialchars($data['alamat']); ?></textarea>

        <label>Nomor Telepon</label>
        <input type="tel"
               name="nomor_telp"
               maxlength="20"
               value="<?= htmlspecialchars($data['nomor_telp']); ?>"
               required>

        <button type="submit" name="update">
            UPDATE PELANGGAN
        </button>

    </form>

    <br>

    <a href="pelanggan.php">Kembali ke Data Pelanggan</a>

</div>

</body>
</html>
