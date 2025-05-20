<?php
include '../../route/koneksi.php';



$folder_upload = "Barang/";

if (!is_dir($folder_upload)) {
    mkdir($folder_upload, 0755, true);
}

$id_barang     = $_POST['id_barang'];
$Nama_Barang   = $_POST['namabarang'];
$Keterangan    = $_POST['keterangan'];
$Stok          = $_POST['stok'];
$Harga_Barang  = $_POST['harga'];
$Kategori      = $_POST['kategori']; // Tambahan kategori
$Unggulan = isset($_POST['unggulan']) ? 1 : 0;

$query = mysqli_query($koneksi, "SELECT gambar FROM barang WHERE id_Barang='$id_barang'");
$data_lama = mysqli_fetch_assoc($query);
$gambar_lama = $data_lama['Gambar'];

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
    $file_tmp = $_FILES['gambar']['tmp_name'];
    $file_name = basename($_FILES['gambar']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($file_ext, $allowed_ext)) {
        $new_file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "", $file_name);
        $target_file = $folder_upload . $new_file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            if (!empty($gambar_lama) && file_exists($folder_upload . $gambar_lama)) {
                unlink($folder_upload . $gambar_lama);
            }

            // Update data dengan gambar baru
            mysqli_query($koneksi, "UPDATE barang SET 
                Nama_Barang='$Nama_Barang',
                Keterangan='$Keterangan',
                Gambar='$new_file_name',
                Stok='$Stok',
                Harga_Sewa='$Harga_Barang',
                Kategori='$Kategori',
                unggulan='$Unggulan'
                WHERE ID_Barang='$id_barang'");
                
            header("location:index.php?pesan=update");
            exit;
        } else {
            echo "Gagal mengupload gambar baru.";
            exit;
        }
    } else {
        echo "Format gambar tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.";
        exit;
    }
} else {
    // Update data tanpa mengganti gambar
    mysqli_query($koneksi, "UPDATE barang SET 
        Nama_Barang='$Nama_Barang',
        Keterangan='$Keterangan',
        Stok='$Stok',
        Harga_Sewa='$Harga_Barang',
        Kategori='$Kategori',
        unggulan='$Unggulan'
        WHERE ID_Barang='$id_barang'");

    header("location:index.php?pesan=update");
    exit;
}
?>
