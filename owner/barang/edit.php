<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Edit Data Barang</title>
</head>
<body>
    <!-- sidebar -->
    <?php include('../layout/sidebar.php'); ?>
    <!-- sidebar -->

    <div style="margin-left:25%; padding:20px;">
        <h3>Edit Data Barang</h3>

        <?php
        include "../../route/koneksi.php";

        if (isset($_GET['id_barang'])) {
            $id_barang = intval($_GET['id_barang']); 
            $stmt = mysqli_prepare($koneksi, "SELECT * FROM barang WHERE id_barang = ?");
            mysqli_stmt_bind_param($stmt, "i", $id_barang);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($data = mysqli_fetch_assoc($result)) {
        ?>
                <form action="update.php" method="post" enctype="multipart/form-data" class="w3-container w3-card-4 w3-light-grey">
                    <input type="hidden" name="id_barang" value="<?php echo $data['id_barang']; ?>">

                    <p>
                        <label>Nama Barang</label>
                        <input class="w3-input w3-border" type="text" name="namabarang" value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required>
                    </p>

                    <p>
                        <label>Keterangan</label>
                        <input class="w3-input w3-border" type="text" name="keterangan" value="<?php echo htmlspecialchars($data['keterangan']); ?>" required>
                    </p>
                    <p>
                        <label >kategori</label>
                    <select class="form-control" id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="tenda">Tenda</option>
                        <option value="perlengkapan masak">Perlengkapan Masak</option>
                        <option value="perlengkapan">Perlengkapan Camping</option>
                    </select>
                    </p>
                    <p>
                        <label >Unggulan</label>
                        <input type="checkbox" name="unggulan" value="<?php echo $data['unggulan'];?>"> Jadikan Produk Unggulan
                    </p>
                    <p>
                        <label>Gambar</label><br>
                        <input type="file" name="gambar" accept="image/*" class="w3-input w3-border">
                        <?php if (!empty($data['gambar'])) { ?>
                            <br>
                            <img src="Barang/<?php echo htmlspecialchars($data['gambar']); ?>" alt="Gambar Barang" style="width:100px; height:auto;">
                            <p class="w3-small">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                        <?php } ?>
                    </p>

                    <p>
                        <label>Stok Barang</label>
                        <input class="w3-input w3-border" type="number" name="stok" min="0" value="<?php echo htmlspecialchars($data['stok']); ?>" required>
                    </p>

                    <p>
                        <label>Harga Sewa (per hari)</label>
                        <input class="w3-input w3-border" type="number" name="harga" step="0.01" min="0" value="<?php echo htmlspecialchars($data['harga_sewa']); ?>" required>
                    </p>

                    <p>
                        <button class="w3-button w3-blue" type="submit">Simpan Perubahan</button>
                    </p>
                </form>
        <?php
            } else {
                echo "<p class='w3-red w3-padding'>Data tidak ditemukan.</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p class='w3-red w3-padding'>ID Barang tidak ditemukan.</p>";
        }
        ?>
    </div>
</body>
</html>