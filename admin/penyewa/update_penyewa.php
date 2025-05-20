<?php
include '../../route/koneksi.php';

$id_penyewa     = $_POST['id_penyewa'];
$nama_penyewa   = $_POST['namapenyewa'];
$alamat         = $_POST['alamat'];
$no_hp          = $_POST['no_hp'];
$email          = $_POST['email'];
$password       = $_POST['password'];

$query = "UPDATE penyewa 
          SET nama_penyewa='$nama_penyewa', 
              alamat='$alamat', 
              no_hp='$no_hp', 
              email='$email', 
              password='$password' 
          WHERE id_penyewa='$id_penyewa'";

$result = mysqli_query($koneksi, $query);

if ($result) {
    header("Location: index.php?pesan=update");
} else {
    echo "Gagal update data: " . mysqli_error($koneksi);
}
?>
