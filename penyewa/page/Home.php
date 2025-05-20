<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subang Outdoor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    <?php include("../layout/home.css"); ?>
  </style>
</head>
<?php include("../layout/navbar.php"); ?>
<body>

<!-- Header -->
<div class="header">
  <div class="logo-container"></div>
  <div class="title">SUBANG OUTDOOR</div>
  <div class="subtitle">BACK TO NATURE</div>
  <div class="tagline">
    Tempat Sewa Alat Camping <span class="highlight">Terpercaya Di Subang</span>
  </div>
</div>

<!-- Section Barang -->
<div class="container my-5">
  <h2 class="mb-4 text-center">Daftar Barang yang Tersedia</h2>
  <div class="row g-4">
    <?php
    include '../../route/koneksi.php';
    $query = "SELECT * FROM barang";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <img src="../../admin/barang/Barang/<?php echo $row['gambar']; ?>" class="card-img-top" alt="<?php echo $row['nama_barang']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo $row['nama_barang']; ?></h5>
              <p class="card-text"><?php echo $row['keterangan']; ?></p>
              <p class="card-text"><strong>Kategori:</strong> <?php echo $row['kategori']; ?></p>
              <p class="card-text"><strong>Stok:</strong> <?php echo $row['stok']; ?></p>
              <p class="card-text text-primary"><strong>Harga Sewa:</strong> Rp <?php echo number_format($row['harga_sewa'], 0, ',', '.'); ?></p>
              <a href="#" class="btn btn-success w-100">Tambahkan ke Keranjang</a>
            </div>
          </div>
        </div>
    <?php
      }
    } else {
      echo "<p class='text-center'>Tidak ada data barang.</p>";
    }
    ?>
  </div>
</div>

</body>
</html>
