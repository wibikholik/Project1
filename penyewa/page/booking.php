<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
     <?php include("../layout/booking.css"); ?>
  </style>
</head>
<body>
<?php include("../layout/navbar.php"); ?>
<?php include("../../route/koneksi.php"); ?>
<div class="container">
<?php
if (isset($_GET['id_barang'])) {
  $id_barang = $_GET['id_barang'];
  $stmt = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang = '$id_barang'");
  $row = mysqli_fetch_assoc($stmt);

  if ($row) {
?>
  <div class="detail-wrapper">
    <div class="image-section">
      <img src="../../admin/barang/Barang/<?php echo $row['gambar']; ?>" class="main-image" alt="<?php echo $row['nama_barang']; ?>">
    </div>

    <div class="info-section">
      <h3><?php echo $row['nama_barang']; ?></h3>
      <p><strong>Rp <?php echo number_format($row['harga_sewa'], 0, ',', '.'); ?></strong></p>
      <p>Kategori: <?php echo $row['kategori']; ?> orang</p>
      <p>Keterangan: <?php echo $row['keterangan']; ?></p>
      <p>Stok: <?php echo $row['stok']; ?></p>

      <form action="../controller/tambah_keranjang.php" method="POST">
        <input type="hidden" name="id_barang" value="<?php echo $row['id_barang']; ?>">

        <label for="tanggal">Tanggal Sewa</label>
        <div class="form-date">
          <input type="date" name="tanggal_sewa" required>
          <span>&gt;</span>
          <input type="date" name="tanggal_kembali" required>
        </div>

        <button type="submit" name="submit" class="btn-submit">Tambahkan ke keranjang</button>
      </form>
    </div>
  </div>
<?php
  } else {
    echo "<p>Barang tidak ditemukan.</p>";
  }
} else {
  echo "<p>ID barang tidak diberikan.</p>";
}
?>
<!-- <div class="produk-unggulan">
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
  </div> -->
</body>
</html>
