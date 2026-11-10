
<?php
session_start();
include "koneksi.php";

// Hak akses khusus admin
if (!isset($_SESSION['id_user']) ||
    $_SESSION['status'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Mengambil data pelanggan
$query = mysqli_query($conn,
    "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pelanggan</title>

    <style>
        body {
            font-family: Arial;
            background: #f1f5f9;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #2563eb;
            color: white;
        }

        a {
            text-decoration: none;
        }

        .tambah {
            background: green;
            color: white;
            padding: 10px;
        }

        .edit {
            background: orange;
            color: white;
            padding: 7px;
        }

        .hapus {
            background: red;
            color: white;
            padding: 7px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>DATA PELANGGAN</h2>

    <a href="dashboard_admin.php">Kembali ke Dashboard</a>

    <br><br>

    <a href="tambah_pelanggan.php" class="tambah">
        + Tambah Pelanggan
    </a>

    <table>
        <tr>
            <th>No</th>
            <th>ID Pelanggan</th>
            <th>Nama Pelanggan</th>
            <th>Alamat</th>
            <th>Nomor Telepon</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {
        ?>

        <tr>
            <td><?= $no++; ?></td>
            <td><?= $data['id_pelanggan']; ?></td>
            <td><?= htmlspecialchars($data['nama_pelanggan']); ?></td>
            <td><?= htmlspecialchars($data['alamat']); ?></td>
            <td><?= htmlspecialchars($data['nomor_telp']); ?></td>

            <td>
                <a class="edit"
                   href="edit_pelanggan.php?id=<?= $data['id_pelanggan']; ?>">
                    Edit
                </a>

                <form action="hapus_pelanggan.php"
                      method="POST"
                      style="display:inline;"
                      onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">

                    <input type="hidden"
                           name="id_pelanggan"
                           value="<?= $data['id_pelanggan']; ?>">

                    <button type="submit" class="hapus">
                        Hapus
                    </button>

                </form>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>
