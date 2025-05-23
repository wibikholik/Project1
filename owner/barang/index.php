<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <title>Barang</title>
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
    $query = "SELECT * FROM barang";
    $result = mysqli_query($koneksi, $query);
    ?>

<!-- sidebar -->
 <?php include('../layout/sidebar.php');?>
<!-- sidebar -->
<div style="margin-left:25%">
<a href="tambah_barang.php">Tambah Barang</a>
    

    <div class="card-body">
        <table class="table table-responsive table-striped">
        <thead>
    <tr>
        <th>ID Barang</th>
        <th>Foto</th>
        <th>Nama Barang</th>
        <th>Keterangan</th>
        <th>Kategori</th>
        <th>Stok</th>
        <th>Harga Sewa</th>
        <th>Unggulan</th> <!-- Kolom Baru -->
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id_barang']; ?></td>
            <td>
                <img src="Barang/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_barang']; ?>" style="width: 100px; height: auto;">
            </td>
            <td><?php echo $row['nama_barang']; ?></td>
            <td><?php echo $row['keterangan']; ?></td>
            <td><?php echo $row['kategori']; ?></td>
            <td><?php echo $row['stok']; ?></td>
            <td><?php echo number_format($row['harga_sewa'], 2); ?></td>
            <td>
                <?php if ($row['unggulan'] == 1): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif; ?>
            </td>
            <td>
                <a href="hapus.php?id_barang=<?php echo $row['id_barang'];?>" class="fas fa-trash"  style='font-size:24px' onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');"></a>
                <a href="edit.php?id_barang=<?php echo $row['id_barang']; ?>" class="fas fa-edit" style='font-size:24px'></a>
            </td>
        </tr>
    <?php } ?>
</tbody>

        </table>
    </div>
</div>
   
</body>
</html>
