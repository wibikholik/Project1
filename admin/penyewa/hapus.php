<?php
include '../../route/koneksi.php';


$id_penyewa = $_GET['id_penyewa'];


mysqli_query($koneksi, "DELETE FROM penyewa WHERE id_penyewa = '$id_penyewa'");

// Redirect ke halaman dashboard setelah penghapusan data
header("location: index.php?pesan=hapus");
?>
