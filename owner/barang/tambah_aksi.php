<?php
include '../../route/koneksi.php'; 


$folder_upload = "Barang/";


if (!is_dir($folder_upload)) {
    mkdir($folder_upload, 0755, true);
}

$Nama_Barang   = $_POST['namabarang'];
$Keterangan    = $_POST['keterangan'];
$Stok          = $_POST['stok'];
$Harga_Barang  = $_POST['harga'];
$Kategori = $_POST['kategori'];
$Unggulan = isset($_POST['unggulan']) ? 1 : 0;



if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
    $file_tmp = $_FILES['gambar']['tmp_name'];
    $file_name = basename($_FILES['gambar']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($file_ext, $allowed_ext)) {
        
        $new_file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "", $file_name);
        $target_file = $folder_upload . $new_file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            // Query insert data ke database dengan nama file gambar
            mysqli_query($koneksi, "INSERT INTO barang 
                (Nama_Barang, Keterangan, Gambar, Stok, Harga_Sewa,kategori,unggulan) 
                VALUES ('$Nama_Barang', '$Keterangan', '$new_file_name', '$Stok', '$Harga_Barang','$Kategori','$Unggulan')");

            header("location:index.php?pesan=input");
            exit;
        } else {
            echo "Gagal mengupload gambar.";
            exit;
        }
    } else {
        echo "Format gambar tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.";
        exit;
    }
} else {
    echo "Gambar belum diupload atau terjadi kesalahan saat upload.";
    exit;
}
?>
