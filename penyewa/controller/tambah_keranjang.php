<?php
include '../../route/koneksi.php';

$id_penyewa = 1; // sementara, nanti bisa diganti dengan session login

// Pastikan id_barang dan jumlah sudah dikirim via POST
if (isset($_POST['id_barang']) && isset($_POST['jumlah'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah = (int) $_POST['jumlah']; // cast ke integer agar aman

    if ($jumlah <= 0) {
        echo "<script>alert('Jumlah harus lebih dari 0.'); window.history.back();</script>";
        exit;
    }

    // Ambil data barang untuk harga
    $hargaQuery = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang = '$id_barang'");
    $barang = mysqli_fetch_assoc($hargaQuery);

    if ($barang) {
        $harga_sewa = $barang['harga_sewa'];

        // Cek apakah barang sudah ada di keranjang
        $cek = mysqli_query($koneksi, "SELECT * FROM carts WHERE id_penyewa = '$id_penyewa' AND id_barang = '$id_barang'");

        if (mysqli_num_rows($cek) > 0) {
            // Update jumlah dan harga di carts
            $cart = mysqli_fetch_assoc($cek);
            $jumlah_baru = $cart['jumlah'] + $jumlah;
            $harga_baru = $harga_sewa * $jumlah_baru;

            $update = mysqli_query($koneksi, "UPDATE carts SET jumlah = '$jumlah_baru', harga = '$harga_baru' WHERE id_penyewa = '$id_penyewa' AND id_barang = '$id_barang'");

            if ($update) {
                echo "<script>alert('Jumlah barang di keranjang berhasil diperbarui.'); window.location.href='../page/keranjang.php';</script>";
            } else {
                echo "<script>alert('Gagal memperbarui keranjang.'); window.history.back();</script>";
            }
        } else {
            // Insert data baru ke carts
            $harga = $harga_sewa * $jumlah;

            $insert = mysqli_query($koneksi, "INSERT INTO carts (id_penyewa, id_barang, jumlah, harga) VALUES ('$id_penyewa', '$id_barang', '$jumlah', '$harga')");

            if ($insert) {
                echo "<script>alert('Barang berhasil ditambahkan ke keranjang.'); window.location.href='../page/keranjang.php';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan ke keranjang.'); window.history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Barang tidak ditemukan.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Data tidak lengkap.'); window.history.back();</script>";
}
?>
