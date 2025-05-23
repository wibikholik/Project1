<?php
include '../../route/koneksi.php';

if (isset($_GET['id_barang'])) {
    $id_barang = intval($_GET['id_barang']); // konversi ke integer agar lebih aman

    mysqli_query($koneksi, "DELETE FROM carts WHERE id_barang = '$id_barang'");
     mysqli_query($koneksi, "DELETE FROM transaksi WHERE id_barang = '$id_barang'");
    // Jalankan query
    $query = mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang = '$id_barang'");

    if ($query) {
        header("location: index.php?pesan=hapus");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    echo "ID Barang tidak ditemukan.";
}
?>
