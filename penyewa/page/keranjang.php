<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Keranjang Belanja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php include("../layout/navbar.php"); ?>

<div class="container mt-5">
  <h5>SUBANG OUTDOOR | Keranjang Belanja</h5>

  <?php
  include '../../route/koneksi.php';
  $id_penyewa = 1; // Sementara, nanti gunakan ID dari session

  $query = "SELECT carts.id, barang.gambar, barang.nama_barang, carts.jumlah, carts.harga
            FROM carts
            JOIN barang ON carts.id_barang = barang.id_barang
            WHERE carts.id_penyewa = '$id_penyewa'";

  $result = mysqli_query($koneksi, $query);

  if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
  }

  if (mysqli_num_rows($result) > 0) {
    echo '<form method="POST" action="booking.php" onsubmit="return validateCheckout()">';
    echo '<table class="table mt-4">
            <thead>
              <tr>
                <th></th>
                <th>Gambar</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>';

    $no = 0;

    while ($row = mysqli_fetch_assoc($result)) {
      $subtotal = $row['jumlah'] * $row['harga'];
      echo '<tr>
        <td><input type="checkbox" class="item-checkbox" name="selected_items[]" value="' . $row['id'] . '" onchange="toggleCheckoutButton(); updateTotal()"></td>
        <td><img src="../../admin/barang/Barang/' . $row['gambar'] . '" alt="' . htmlspecialchars($row['nama_barang']) . '" style="width: 80px;"></td>
        <td>' . htmlspecialchars($row['nama_barang']) . '</td>
        <td>
          <input type="number" class="form-control quantity-input" name="jumlah[' . $row['id'] . ']" value="' . $row['jumlah'] . '" min="1" data-price="' . $row['harga'] . '" onchange="updateSubtotal(this); updateTotal()" />
        </td>
        <td>Rp. ' . number_format($row['harga'], 0, ",", ".") . '</td>
        <td class="subtotal">Rp. ' . number_format($subtotal, 0, ",", ".") . '</td>
        <td><a href="../controller/hapus.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin hapus?\')">Hapus</a></td>
      </tr>';
      $no++;
    }

    echo '</tbody></table>';

    // Bagian total dan tombol
    echo '
      <div class="d-flex align-items-center justify-content-between border-top pt-3 mt-3">
        <div>
         <th><input type="checkbox" id="select-all" /></th>
          <label class="mb-0"><strong>Pilih Semua (' . $no . ')</strong></label>
          
        </div>
        <div class="fw-bold">Total: <span id="total-display">Rp. 0</span></div>
        <button type="submit" id="checkout-btn" class="btn btn-dark" disabled>Booking</button>
      </div>
    </form>';

  } else {
    echo '<div class="alert alert-info mt-4">Keranjang belanja kosong.</div>';
  }
  ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Pilih Semua
document.getElementById('select-all')?.addEventListener('change', function () {
  const checked = this.checked;
  document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = checked);
  toggleCheckoutButton();
  updateTotal();
});

// Toggle tombol booking
function toggleCheckoutButton() {
  const anyChecked = [...document.querySelectorAll('.item-checkbox')].some(cb => cb.checked);
  document.getElementById('checkout-btn').disabled = !anyChecked;
}

// Validasi saat submit
function validateCheckout() {
  const anyChecked = [...document.querySelectorAll('.item-checkbox')].some(cb => cb.checked);
  if (!anyChecked) {
    alert("Silakan pilih minimal satu barang.");
    return false;
  }
  return true;
}

// Update subtotal saat jumlah berubah
function updateSubtotal(input) {
  const harga = parseInt(input.dataset.price);
  let jumlah = parseInt(input.value);
  if (isNaN(jumlah) || jumlah < 1) {
    jumlah = 1;
    input.value = 1;
  }
  const subtotal = harga * jumlah;
  input.closest('tr').querySelector('.subtotal').textContent = "Rp. " + subtotal.toLocaleString("id-ID");
}

// Update total semua item terpilih
function updateTotal() {
  let total = 0;
  document.querySelectorAll('.item-checkbox').forEach(cb => {
    if (cb.checked) {
      const row = cb.closest('tr');
      const harga = parseInt(row.querySelector('.quantity-input').dataset.price);
      const jumlah = parseInt(row.querySelector('.quantity-input').value);
      total += harga * jumlah;
    }
  });
  document.getElementById('total-display').textContent = "Rp. " + total.toLocaleString("id-ID");
}

// Inisialisasi saat load
window.addEventListener('DOMContentLoaded', () => {
  updateTotal();
  toggleCheckoutButton();
});
</script>

</body>
</html>
