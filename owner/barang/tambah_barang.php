<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Input Data Barang</title>
</head>
<body>
    <!-- sidebar -->
 <?php include('../layout/sidebar.php');?>
<!-- sidebar -->
<div style="margin-left:25%">
    <h3>Input Data Barang</h3>
    <form action="tambah_aksi.php" method="post"  class="w3-container w3-card-4 w3-light-grey" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Nama Barang</td>
                <td><input type="text" name="namabarang" required class="w3-input w3-border"></td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>
                    <select class="form-control" id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="tenda">Tenda</option>
                        <option value="perlengkapan masak">Perlengkapan Masak</option>
                        <option value="perlengkapan">Perlengkapan Camping</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Produk Unggulan</td>
                <td><input type="checkbox" name="unggulan" value="1"> Jadikan Produk Unggulan</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td><input type="text" name="keterangan" class="w3-input w3-border" required></td>
            </tr>
            <tr>
                <td>Gambar</td>
                <td><input type="file" name="gambar" accept="image/*" class="w3-input w3-border" required></td>
            </tr>
            <tr> 
                <td>Stok Barang</td>
                <td><input type="number" name="stok" min="0"  class="w3-input w3-border" required></td>
            </tr>
            <tr>
                <td>Harga Sewa (per hari)</td>
                <td><input type="number" name="harga" min="0" step="0.01" class="w3-input w3-border" required></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" class="w3-button w3-blue" value="Tambah"></td>
            </tr>
        </table>
    </form>
    </div>
</body>
</html>
