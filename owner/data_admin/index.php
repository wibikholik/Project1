<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <title>Admin</title>
</head>
<style>
        <?php include('../layout/style.css');?>
    </style>

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
    $query = "SELECT * FROM admin";
    $result = mysqli_query($koneksi, $query);
    ?>

<!-- sidebar -->
 <?php include('../layout/sidebar.php');?>
<!-- sidebar -->
<div style="margin-left:25%">
<a href="tambah_admin.php">Tambah Admin</a>
    

    <div class="card-body">
        <table class="table table-responsive table-striped">
        <thead>
    <tr>
        <th>ID admin</th>
        <th>Username</th>
        <th>Nama Admin</th>
        <th>Alamat</th>
        <th>No HP</th>
        <th>Email</th>
        <th>Password</th> <!-- Kolom Baru -->
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id_admin']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['nama_admin']; ?></td>
            <td><?php echo $row['alamat']; ?></td>
            <td><?php echo $row['no_hp']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['password']; ?></td>
            <td>
                <a href="hapus_admin.php?id_admin=<?php echo $row['id_admin'];?>" class="fas fa-trash"  style='font-size:24px' onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"></a>
                <a href="edit_admin.php?id_admin=<?php echo $row['id_admin']; ?>" class="fas fa-edit" style='font-size:24px'></a>
            </td>
        </tr>
    <?php } ?>
</tbody>

        </table>
    </div>
</div>
   
</body>
</html>
