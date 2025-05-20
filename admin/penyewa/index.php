<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Penyewa</title>
    <style>
        <?php include('../layout/style.css');?>
    </style>
</head>

<body>
<?php
    if (isset($_GET['pesan'])) {
        $pesan = $_GET['pesan'];  
        if ($pesan == "input") {
            echo "<p>Data Berhasil ditambahkan</p>";
        } else if ($pesan == "hapus") {
            echo "<p>Data Berhasil dihapus</p>";
        }
    elseif (isset($_GET['pesan'])) {
        $pesan = $_GET['pesan'];  
        if ($pesan == "input") {
            echo "<p>Data Berhasil diupdate</p>";
        } else if ($pesan == "update") {
            echo "<p>Data Berhasil diupdate</p>";
        }
    }}
    ?>
    <?php
    include '../../route/koneksi.php';
    $query = "SELECT * FROM penyewa";
    $result = mysqli_query($koneksi, $query);
    ?>

<!-- sidebar -->
 <?php include('../layout/sidebar.php');?>
<!-- sidebar -->
<div style="margin-left:25%">
<a href="tambah_user.php">Tambah User</a>
    

    <div class="card-body">
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>ID Penyewa</th>
                    <th>Nama Penyewa</th>
                    <th>alamat</th>
                    <th>No_hp</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id_penyewa']; ?></td>
                        <td><?php echo $row['nama_penyewa']; ?></td>
                        <td><?php echo $row['alamat']; ?></td>
                        <td><?php echo $row['no_hp']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['password']; ?></td>
                       
                        <td>
                            <a href="hapus.php?id_penyewa=<?php echo $row['id_penyewa'];?>" class="fas fa-trash" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');"></a>
                            <a href="edit_penyewa.php?id_penyewa=<?php echo $row['id_penyewa']; ?>" class="fas fa-edit"></a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
   
</body>
</html>
