<?php
include '../../route/koneksi.php';


$id_admin = $_GET['id_admin'];


mysqli_query($koneksi, "DELETE FROM admin WHERE id_admin = '$id_admin'");

// Redirect ke halaman dashboard setelah penghapusan data
header("location: index.php?pesan=hapus");
?>