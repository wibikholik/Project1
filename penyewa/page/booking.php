<?php
include '../../route/koneksi.php';

$id_penyewa = 1; // Sesuaikan dengan session atau login user

// Ambil data yang dipost dari keranjang.php
$selected_ids = $_POST['selected_items'] ?? [];
$jumlah_items = $_POST['jumlah'] ?? [];

if (!$selected_ids) {
    die("Tidak ada item yang dipilih.");
}

// Buat string ID untuk query IN
$id_list = implode(',', array_map('intval', $selected_ids));

// Update jumlah pada tabel carts sesuai input dari keranjang.php
foreach ($selected_ids as $cart_id) {
    if (isset($jumlah_items[$cart_id])) {
        $jumlah_baru = (int)$jumlah_items[$cart_id];
        if ($jumlah_baru < 1) $jumlah_baru = 1;

        $cart_id = (int)$cart_id;
        $update_sql = "UPDATE carts SET jumlah = $jumlah_baru WHERE id = $cart_id AND id_penyewa = '$id_penyewa'";
        mysqli_query($koneksi, $update_sql);
    }
}

// Ambil data terbaru setelah update
$query_carts = "SELECT carts.id, carts.id_barang, barang.gambar, barang.nama_barang, carts.jumlah, carts.harga
                FROM carts
                JOIN barang ON carts.id_barang = barang.id_barang
                WHERE carts.id_penyewa = '$id_penyewa' AND carts.id IN ($id_list)";

$result_carts = mysqli_query($koneksi, $query_carts);

if (!$result_carts) {
    die("Query error: " . mysqli_error($koneksi));
}

// Selanjutnya, kamu bisa tampilkan data $result_carts sesuai kebutuhan checkout...
// Ambil data penyewa
$query_penyewa = "SELECT * FROM penyewa WHERE id_penyewa = '$id_penyewa'";
$result_penyewa = mysqli_query($koneksi, $query_penyewa);
$penyewa = mysqli_fetch_assoc($result_penyewa);

// Ambil data carts yang dipilih


// Metode pembayaran
$query_metode = "SELECT * FROM metode_pembayaran";
$result_metode = mysqli_query($koneksi, $query_metode);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <?php include("../layout/navbar.php"); ?>
  <style>
    <?php include("../layout/checkout.css"); ?>
  </style>
</head>
<body>

<div class="container" style="margin:20px 0;">
  <div class="info" style="display:inline-block;">
    <strong><?= htmlspecialchars($penyewa['nama_penyewa']) ?></strong><br>
    <?= htmlspecialchars($penyewa['no_hp']) ?><br>
    <small><?= htmlspecialchars($penyewa['alamat']) ?></small>
  </div>
</div>

<hr>

<h3>Daftar Pesanan</h3>

<?php if(mysqli_num_rows($result_carts) > 0): 
  // simpan data carts ke array untuk JS
  $cart_items = [];
  mysqli_data_seek($result_carts, 0); // reset pointer
  while($row = mysqli_fetch_assoc($result_carts)) {
    $cart_items[] = $row;
  }
?>
<form action="../controller/prosescheckout.php" method="post" id="checkout-form" style="display: flex; gap: 30px; align-items: flex-start;" onsubmit="return validateDates()">

  <!-- Daftar pesanan -->
  <div style="flex: 0 0 700px; max-width: 700px;">
    <h4>Daftar Pesanan</h4>
    <?php foreach ($cart_items as $row): ?>
      <div class="pesanan-item w3-padding w3-margin-bottom" style="display: flex; align-items: center; gap: 15px;">
        <img src="../../admin/barang/Barang/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_barang']) ?>" style="height: 80px; flex-shrink: 0;">
        <div class="pesanan-detail">
          <strong><?= htmlspecialchars($row['nama_barang']) ?> (<?= $row['jumlah'] ?>)</strong>
          <div>Harga/hari: Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
          <div id="subtotal-<?= $row['id'] ?>">Subtotal: Rp 0</div>
        </div>

        <!-- Kirim data barang ke proses -->
        <input type="hidden" name="items[<?= $row['id'] ?>][id_barang]" value="<?= $row['id_barang'] ?>">
        <input type="hidden" name="items[<?= $row['id'] ?>][jumlah]" value="<?= $row['jumlah'] ?>">
        <input type="hidden" name="items[<?= $row['id'] ?>][harga]" value="<?= $row['harga'] ?>">
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Form tanggal sewa dan kembali + metode pembayaran + submit -->
  <div style="min-width: 300px;">
    <h4>Tanggal Sewa</h4>
    <label for="tanggal_sewa" style="font-weight: bold; font-size: 14px;">Sewa:</label>
    <input type="date" name="tanggal_sewa" id="tanggal_sewa" class="w3-input w3-border" required
      style="font-size: 14px; padding: 6px; margin-bottom: 10px;">

    <label for="tanggal_kembali" style="font-weight: bold; font-size: 14px;">Kembali:</label>
    <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="w3-input w3-border" required
      style="font-size: 14px; padding: 6px; margin-bottom: 10px;">

    <h4>Metode Pembayaran</h4>
    <?php
      mysqli_data_seek($result_metode, 0);
      while ($metode = mysqli_fetch_assoc($result_metode)): ?>
        <label style="margin-right:15px; cursor: pointer;">
          <input type="radio" name="id_metode" value="<?= $metode['id_metode'] ?>" required />
          <img src="../../admin/metode/<?= htmlspecialchars($metode['gambar_metode']) ?>" alt="<?= htmlspecialchars($metode['nama_metode']) ?>" style="height:40px;">
        </label>
    <?php endwhile; ?>

    <h4>Total Bayar</h4>
    <div id="total_bayar_display" style="font-weight: bold; font-size: 18px; margin-bottom: 15px;">Rp 0</div>
    <input type="hidden" name="total_harga_sewa" id="total_harga_sewa" value="0">

    <div class="w3-margin-top">
      <button type="submit" class="w3-button w3-dark-grey">Konfirmasi</button>
    </div>
  </div>

</form>

<script>
  // Data carts dari PHP ke JS
  const cartItems = <?= json_encode($cart_items) ?>;

  function hitungSelisihHari(tgl1, tgl2) {
    const date1 = new Date(tgl1);
    const date2 = new Date(tgl2);
    const diffTime = date2 - date1;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays > 0 ? diffDays : 0;
  }

  function updateTotalBayar() {
    const tglSewa = document.getElementById("tanggal_sewa").value;
    const tglKembali = document.getElementById("tanggal_kembali").value;
    const hariSewa = hitungSelisihHari(tglSewa, tglKembali);

    if (hariSewa === 0) {
      // Reset subtotal dan total kalau tanggal tidak valid
      cartItems.forEach(item => {
        const subtotalElem = document.getElementById(`subtotal-${item.id}`);
        if(subtotalElem) {
          subtotalElem.innerText = "Subtotal: Rp 0";
        }
      });
      document.getElementById("total_bayar_display").innerText = "Rp 0";
      document.getElementById("total_harga_sewa").value = 0;
      return;
    }

    let total = 0;
    cartItems.forEach(item => {
      const subtotal = item.jumlah * item.harga * hariSewa;
      const subtotalElem = document.getElementById(`subtotal-${item.id}`);
      if(subtotalElem) {
        subtotalElem.innerText = "Subtotal: Rp " + subtotal.toLocaleString("id-ID");
      }
      total += subtotal;
    });

    document.getElementById("total_bayar_display").innerText = "Rp " + total.toLocaleString("id-ID");
    document.getElementById("total_harga_sewa").value = total;
  }

  function validateDates() {
    const tglSewa = document.getElementById("tanggal_sewa").value;
    const tglKembali = document.getElementById("tanggal_kembali").value;

    if (!tglSewa || !tglKembali) {
      alert("Tanggal sewa dan kembali harus diisi.");
      return false;
    }

    if (tglKembali <= tglSewa) {
      alert("Tanggal kembali harus lebih besar dari tanggal sewa.");
      return false;
    }

    if (document.getElementById("total_harga_sewa").value == 0) {
      alert("Total pembayaran tidak boleh 0.");
      return false;
    }

    return true;
  }

  document.getElementById("tanggal_sewa").addEventListener("change", updateTotalBayar);
  document.getElementById("tanggal_kembali").addEventListener("change", updateTotalBayar);

  // Inisialisasi total saat halaman load
  window.addEventListener('DOMContentLoaded', () => {
    updateTotalBayar();
  });
</script>

<?php else: ?>
  <p>Data tidak ditemukan.</p>
<?php endif; ?>

</body>
</html>
