<!DOCTYPE html>
<html lang="id">
<body>

<?php include("../layout/navbar.php"); ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
  <h5>SUBANG OUTDOOR | Keranjang Belanja</h5>

  <?php
  include '../../route/koneksi.php';
  $id_penyewa = 1; //sementara

  $query = "SELECT carts.id, barang.gambar, barang.nama_barang, carts.jumlah, carts.harga
            FROM carts
            JOIN barang ON carts.id_barang = barang.id_barang
            WHERE carts.id_penyewa = '$id_penyewa'";
  $result = mysqli_query($koneksi, $query);

  if (mysqli_num_rows($result) > 0) {
    echo '<table class="table mt-4">
            <tbody>';

    $no = 1;
    $totalBayar = 0;

    while ($row = mysqli_fetch_assoc($result)) {
      $totalBayar += $row['harga'];
      echo '<tr>
              <td><input type="checkbox" class="item-checkbox" name="pilih"></td>
              <td><img src="../../admin/barang/Barang/' . $row['gambar'] . '" alt="' . htmlspecialchars($row['nama_barang']) . '" style="width: 100px;"></td>
              <td>' . htmlspecialchars($row['nama_barang']) . '</td>
               <td>
                <input type="number" class="form-control quantity-input" value="' . $row . '" min="1" 
                  data-price="' . $harga . '" onchange="updateSubtotal(this); updateTotal()">
              </td>
              <td>Rp. ' . number_format($row['harga'], 0, ',', '.') . '</td>
              <td><a href="../controller/hapus.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm">Hapus</a></td>
            </tr>';
      $no++;
    }

    echo '</tbody></table>';

    // Tampilan footer dengan "Pilih Semua", total bayar, dan tombol checkout
    echo '
      <div class="d-flex align-items-center justify-content-between border-top pt-3 mt-3">
        <div>
          <input type="checkbox" id="select-all" />
          <label for="select-all" class="mb-0 ms-2">Pilih Semua (' . ($no-1) . ')</label>
        </div>
        <div class="fw-bold">Rp. ' . number_format($totalBayar, 0, ",", ".") . '</div>
        <a href="checkout.php" class="btn btn-dark">Checkout</a>
      </div>
    ';
  } else {
    echo '<div class="alert alert-info">Keranjang belanja kosong.</div>';
  }
  ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Fungsi pilih semua checkbox
document.getElementById('select-all').addEventListener('change', function() {
  const checked = this.checked;
  document.querySelectorAll('.item-checkbox').forEach(checkbox => {
    checkbox.checked = checked;
  });
});
</script>

</body>
</html>
