<?php
include '../../route/koneksi.php';
session_start();

// Ambil ID penyewa dari session (fallback ke 1 untuk pengujian)
$id_penyewa = $_SESSION['id_penyewa'] ?? 1;

// Hanya izinkan metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak diizinkan.");
}

// Ambil data dari form
$id_metode = $_POST['id_metode'] ?? null;
$selected_items = $_POST['items'] ?? []; // items yang dipilih, bentuk array asosiatif keyed by cart_id
$tanggal_sewa = $_POST['tanggal_sewa'] ?? null;
$tanggal_kembali = $_POST['tanggal_kembali'] ?? null;

if (!$id_metode || empty($selected_items) || !$tanggal_sewa || !$tanggal_kembali) {
    die("Data tidak lengkap. Silakan pilih barang, metode pembayaran, dan isi tanggal sewa & kembali.");
}

// Validasi tanggal sewa dan kembali
$ts_sewa = strtotime($tanggal_sewa);
$ts_kembali = strtotime($tanggal_kembali);

if (!$ts_sewa || !$ts_kembali || $ts_kembali <= $ts_sewa) {
    die("Tanggal kembali harus lebih dari tanggal sewa.");
}

$lama_sewa = ceil(($ts_kembali - $ts_sewa) / (60 * 60 * 24));

// Ambil semua ID carts yang dipilih
$selected_ids = array_keys($selected_items);
$selected_ids_int = array_map('intval', $selected_ids);
$ids_placeholders = implode(',', array_fill(0, count($selected_ids_int), '?'));

// Ambil data carts dan barang yang dipilih
$sql = "SELECT c.id AS cart_id, c.id_barang, c.jumlah, b.harga_sewa AS harga 
        FROM carts c
        JOIN barang b ON c.id_barang = b.id_barang
        WHERE c.id_penyewa = ? AND c.id IN ($ids_placeholders)";

$stmt = mysqli_prepare($koneksi, $sql);
$params = array_merge([$id_penyewa], $selected_ids_int);

// Bind params dinamis
$types = str_repeat('i', count($params));
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$items_db = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items_db[$row['cart_id']] = $row;
}

if (count($items_db) !== count($selected_ids_int)) {
    die("Data keranjang tidak valid.");
}

mysqli_begin_transaction($koneksi);

try {
    // Hitung total harga semua barang
    $total_harga = 0;
    foreach ($selected_ids_int as $cart_id) {
        $item = $items_db[$cart_id];
        $total_harga += $item['harga'] * $item['jumlah'] * $lama_sewa;
    }

    // Insert 1 transaksi untuk semua barang
    $stmtTransaksi = mysqli_prepare($koneksi, "INSERT INTO transaksi (id_penyewa, total_harga_sewa, status, id_metode, tanggal_sewa, tanggal_kembali) VALUES (?, ?, 'belumbayar', ?, ?, ?)");
   mysqli_stmt_bind_param($stmtTransaksi, "idiss", $id_penyewa, $total_harga, $id_metode, $tanggal_sewa, $tanggal_kembali);
    if (!mysqli_stmt_execute($stmtTransaksi)) {
        throw new Exception("Gagal menyimpan transaksi.");
    }
    $id_transaksi = mysqli_insert_id($koneksi);

    // Prepare untuk detail dan update stok
    $stmtDetail = mysqli_prepare($koneksi, "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah_barang, harga_satuan) VALUES (?, ?, ?, ?)");
    $stmtUpdateStok = mysqli_prepare($koneksi, "UPDATE barang SET stok = stok - ? WHERE id_barang = ?");

    // Insert detail transaksi dan update stok
    foreach ($selected_ids_int as $cart_id) {
        $item = $items_db[$cart_id];
        $id_barang = (int) $item['id_barang'];
        $jumlah = (int) $item['jumlah'];
        $harga_per_hari = (float) $item['harga'];

        // Insert ke detail_transaksi
        mysqli_stmt_bind_param($stmtDetail, "iiid", $id_transaksi, $id_barang, $jumlah, $harga_per_hari);
        if (!mysqli_stmt_execute($stmtDetail)) {
            throw new Exception("Gagal menyimpan detail transaksi untuk barang ID: $id_barang");
        }

        // Update stok
       
        
    }

    // Hapus item dari keranjang
    $hapusSQL = "DELETE FROM carts WHERE id_penyewa = ? AND id IN ($ids_placeholders)";
    $hapusStmt = mysqli_prepare($koneksi, $hapusSQL);
    $hapusParams = array_merge([$id_penyewa], $selected_ids_int);
    $hapusTypes = str_repeat('i', count($hapusParams));
    mysqli_stmt_bind_param($hapusStmt, $hapusTypes, ...$hapusParams);
    if (!mysqli_stmt_execute($hapusStmt)) {
        throw new Exception("Gagal menghapus item dari keranjang.");
    }

    // Commit transaksi
    mysqli_commit($koneksi);

    // Redirect ke halaman pembayaran
    header("Location: ../page/pembayaran.php?id_transaksi=$id_transaksi");
    exit;

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    echo "Terjadi kesalahan: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
