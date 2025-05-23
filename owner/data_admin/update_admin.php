<?php
include '../../route/koneksi.php';

$id_admin    = $_POST['id_admin'];
$username   = $_POST['username'];
$nama_admin   = $_POST['nama_admin'];
$alamat         = $_POST['alamat'];
$no_hp          = $_POST['no_hp'];
$email          = $_POST['email'];
$password       = $_POST['password'];

$query = "UPDATE admin 
          SET username='$username', 
              nama_admin='$nama_admin', 
              alamat='$alamat', 
              no_hp='$no_hp', 
              email='$email',
              password='$password' 
          WHERE id_admin='$id_admin'";

$result = mysqli_query($koneksi, $query);

if ($result) {
    header("Location: index.php?pesan=update");
} else {
    echo "Gagal update data: " . mysqli_error($koneksi);
}
?>