<?php
include '../../route/koneksi.php';

$username = $_POST['username'];
$nama_admin = $_POST['nama_admin'];
$alamat = $_POST['alamat'];
$no_hp = $_POST['no_hp'];
$email = $_POST['email'];
$password = $_POST['password'];

mysqli_query($koneksi, "INSERT INTO admin (username, nama_admin, alamat, no_hp, email, password) 
VALUES('$username','$nama_admin','$alamat', $no_hp, '$email', '$password')");

header("location:index.php?pesan=input");
?>