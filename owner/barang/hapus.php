<?php
include '../../route/koneksi.php';


$id_barang = $_GET['id_barang'];


mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang = '$id_barang'");

// Redirect ke halaman dashboard setelah penghapusan data
header("location: index.php?pesan=hapus");
?>
