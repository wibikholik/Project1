<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

  <title>Produk Tenda</title>
  <style>
   <?php include("../layout/sewa.css"); ?>
  </style>
</head>
<body>
<?php include("../layout/navbar.php"); ?>

<div class="container">
  <div class="produk">
    <?php
    include '../../route/koneksi.php';
    $query = "SELECT * FROM barang";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
    ?>
      <div class="card">
        <img src="../../admin/barang/Barang/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_barang']; ?>">
        <p><?php echo $row['nama_barang']; ?></p>
        <p>Stok: <?php echo $row['stok']; ?></p>
        <p class="harga">Rp.<?php echo number_format($row['harga_sewa'], 0, ',', '.'); ?></p>
        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#keranjangModal<?php echo $row['id_barang']; ?>">
          Tambah ke Keranjang
        </button>
        <?php include('../layout/modal.php'); ?>
      </div>
    <?php
      }
    } else {
      echo "<p class='text-center'>Tidak ada data barang.</p>";
    }
    ?>
  </div>

  <div class="produk-unggulan">
    <h3>Produk Unggulan</h3>
    <?php
    $unggulanQuery = "SELECT * FROM barang WHERE unggulan = 1";
    $unggulanResult = mysqli_query($koneksi, $unggulanQuery);

    if (mysqli_num_rows($unggulanResult) > 0) {
      while ($row = mysqli_fetch_assoc($unggulanResult)) {
    ?>
      <div class="unggulan-item">
        <img src="../../admin/barang/Barang/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_barang']; ?>">
        <div>
          <p><?php echo $row['nama_barang']; ?></p>
          <p class="harga">Rp.<?php echo number_format($row['harga_sewa'], 0, ',', '.'); ?></p>
        </div>
      </div>
    <?php
      }
    } else {
      echo "<p>Tidak ada produk unggulan.</p>";
    }
    ?>
  </div>
</div>

</body>
</html>
