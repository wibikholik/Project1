<?php
include '../../route/koneksi.php'; 


$nama_penyewa   = $_POST['namapenyewa'];
$alamat    = $_POST['alamat'];
$no_hp        = $_POST['no_hp'];
$email  = $_POST['email'];
$password  = $_POST['password'];
        

             mysqli_query($koneksi, "INSERT INTO penyewa VALUES ('','$nama_penyewa', '$alamat', '$no_hp', '$email', '$p assword')");

            header("location:index.php?pesan=input");

?>
