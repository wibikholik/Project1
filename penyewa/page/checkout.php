<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<title>Checkout</title>
<?php include("../layout/navbar.php"); ?>
<style>
  <?php include("../layout/checkout.css"); ?>
</style>
</head>
<body>
<?php
include '../../route/koneksi.php';

$id_penyewa = 1;

// Ambil data penyewa
$query_penyewa = "SELECT * FROM penyewa WHERE id_penyewa = '$id_penyewa'";
$result_penyewa = mysqli_query($koneksi, $query_penyewa);
$penyewa = mysqli_fetch_assoc($result_penyewa);

// Ambil data carts milik penyewa
$query_carts = "SELECT carts.id, barang.gambar, barang.nama_barang, carts.jumlah, carts.harga, carts.tanggal_sewa, carts.tanggal_kembali
FROM carts
JOIN barang ON carts.id_barang = barang.id_barang
WHERE carts.id_penyewa = '$id_penyewa'";
$result_carts = mysqli_query($koneksi, $query_carts);

// Ambil data metode pembayaran dari database
$query_metode = "SELECT * FROM metode_pembayaran";
$result_metode = mysqli_query($koneksi, $query_metode);
?>

<div class="header" style="margin:20px 0;">
  <div class="icon" style="font-size: 2em;">📍</div>
  <div class="info" style="display:inline-block; margin-left:10px;">
    <div><strong><?= htmlspecialchars($penyewa['nama_penyewa']) ?></strong></div>
    <div><?= htmlspecialchars($penyewa['no_hp']) ?></div>
  </div>
  <div style="font-size: 0.9em; color:#666;"><?= htmlspecialchars($penyewa['alamat']) ?></div>
</div>

<hr />

<h3>Daftar pesanan</h3>

<?php if(mysqli_num_rows($result_carts) > 0): ?>
  <?php 
  $totalBayar = 0;
  while ($row = mysqli_fetch_assoc($result_carts)):
    $totalBayar += $row['harga'];
  ?>
  <div class="pesanan-item">
    <img src="../../admin/barang/Barang/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_barang']) ?>" />
    <div class="pesanan-detail">
      <div class="pesanan-nama"><?= htmlspecialchars($row['nama_barang']) ?> (<?= $row['jumlah'] ?>)</div>
      <div class="pesanan-tanggal"><?= date('d M', strtotime($row['tanggal_sewa'])) ?> - <?= date('d M', strtotime($row['tanggal_kembali'])) ?></div>
    </div>
    <div class="pesanan-harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
  </div>
  <?php endwhile; ?>
<?php else: ?>
  <p>Keranjang masih kosong.</p>
<?php endif; ?>

<h3>Metode pembayaran</h3>
<form action="proses_checkout.php" method="post" enctype="multipart/form-data">
  <?php if(mysqli_num_rows($result_metode) > 0): ?>
    <?php while ($metode = mysqli_fetch_assoc($result_metode)): ?>
      <label class="metode-item">
        <input type="radio" name="id_metode" value="<?= $metode['id_metode'] ?>" required />
        <img src="../../admin/metode/<?= htmlspecialchars($metode['gambar_metode']) ?>" alt="<?= htmlspecialchars($metode['nama_metode']) ?>" />
        <span><?= htmlspecialchars($metode['nama_metode']) ?></span>
      </label>
    <?php endwhile; ?>
  <?php else: ?>
    <p>Tidak ada metode pembayaran tersedia.</p>
  <?php endif; ?>

  <div style="margin-top: 30px; font-weight:bold;">
    Total: Rp <?= number_format($totalBayar, 0, ',', '.') ?>
  </div>

  <input type="hidden" name="id_penyewa" value="<?= $id_penyewa ?>">
  <input type="hidden" name="total_harga_sewa" value="<?= $totalBayar ?>">

  <div style="margin-top: 20px;">
    <button type="submit" class="w3-button w3-dark-grey">Konfirmasi</button>
  </div>
</form>

</body>
</html>
