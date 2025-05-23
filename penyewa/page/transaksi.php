<?php
include '../../route/koneksi.php';
session_start();
$id_penyewa = $_SESSION['id_penyewa'] ?? 1;

$query_transaksi = "
    SELECT t.*, mp.nama_metode, mp.gambar_metode, mp.nomor_rekening
    FROM transaksi t
    JOIN metode_pembayaran mp ON t.id_metode = mp.id_metode
    WHERE t.id_penyewa = ?
    ORDER BY t.id_transaksi DESC
";

$stmt = $koneksi->prepare($query_transaksi);
$stmt->bind_param("i", $id_penyewa);
$stmt->execute();
$result_transaksi = $stmt->get_result();

if (!$result_transaksi) {
    die("Query gagal: " . $koneksi->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Histori Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include("../layout/navbar.php"); ?>

<div class="container mt-4">
  <h4>Histori Transaksi Anda</h4>

  <?php if ($result_transaksi->num_rows < 1): ?>
    <div class="alert alert-info">Belum ada transaksi.</div>
  <?php endif; ?>

  <div class="d-flex flex-wrap gap-3">
    <?php while ($transaksi = $result_transaksi->fetch_assoc()) : ?>
      <div class="card mb-4 shadow-sm" style="min-width: 350px; max-width: 500px; flex: 1;">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <strong>ID Transaksi: <?= htmlspecialchars($transaksi['id_transaksi']); ?></strong><br>
            Status: 
            <span class="badge <?= ($transaksi['status'] === 'Belum Dibayar') ? 'bg-warning text-dark' : 'bg-success'; ?>">
              <?= htmlspecialchars($transaksi['status']); ?>
            </span><br>
            <small>Metode: <?= htmlspecialchars($transaksi['nama_metode']); ?></small>
          </div>
        </div>
        <div class="card-body">
          <?php
          $id_transaksi = $transaksi['id_transaksi'];
          $tanggal_sewa = new DateTime($transaksi['tanggal_sewa']);
          $tanggal_kembali = new DateTime($transaksi['tanggal_kembali']);
          $lama_sewa = $tanggal_sewa->diff($tanggal_kembali)->days;

          $query_detail = "
            SELECT dt.*, b.nama_barang, b.gambar 
            FROM detail_transaksi dt
            JOIN barang b ON dt.id_barang = b.id_barang
            WHERE dt.id_transaksi = ?
          ";
          $stmt_detail = $koneksi->prepare($query_detail);
          $stmt_detail->bind_param("i", $id_transaksi);
          $stmt_detail->execute();
          $result_detail = $stmt_detail->get_result();
          ?>

          <div><strong>Periode Sewa:</strong> <?= $tanggal_sewa->format('d M Y'); ?> - <?= $tanggal_kembali->format('d M Y'); ?></div>
          <div><strong>Lama Sewa:</strong> <?= $lama_sewa; ?> hari</div>

          <?php if ($result_detail->num_rows > 0): ?>
            <table class="table mt-2">
              <thead>
                <tr>
                  <th>Gambar</th>
                  <th>Nama Barang</th>
                  <th>Jumlah</th>
                  <th>Harga Satuan</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $result_detail->fetch_assoc()) : ?>
                  <tr>
                    <td><img src="../../admin/barang/Barang/<?= htmlspecialchars($row['gambar']); ?>" alt="Barang" style="width: 80px;"></td>
                    <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                    <td><?= htmlspecialchars($row['jumlah_barang']); ?></td>
                    <td>Rp<?= number_format($row['harga_satuan'], 0, ',', '.'); ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="text-muted">Tidak ada detail barang.</div>
          <?php endif; ?>

          <div class="d-flex justify-content-between mt-3 align-items-center">
            <div><strong>Total:</strong> Rp<?= number_format($transaksi['total_harga_sewa'], 0, ',', '.'); ?></div>
            <div>
              <?php if (strtolower(str_replace(' ', '', $transaksi['status'])) === 'belumbayar') : ?>
                <form action="pembayaran.php" method="GET" class="d-inline">
                  <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi']; ?>">
                  <button type="submit" class="btn btn-primary btn-sm">Bayar Sekarang</button>
                </form>
              <?php else : ?>
                <span class="text-success">Sudah dibayar</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
