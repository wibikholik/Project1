<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Edit Data Admin</title>
</head>
<body>
    <!-- sidebar -->
    <?php include('../layout/sidebar.php'); ?>
    <!-- sidebar -->

    <div style="margin-left:25%; padding:20px;">
        <h3>Edit Data Admin</h3>

        <?php
        include "../../route/koneksi.php";

        if (isset($_GET['id_admin'])) {
            $id_admin = intval($_GET['id_admin']); 
            $stmt = mysqli_prepare($koneksi, "SELECT * FROM admin WHERE id_admin = ?");
            mysqli_stmt_bind_param($stmt, "i", $id_admin);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($data = mysqli_fetch_assoc($result)) {
        ?>
                <form action="update_admin.php" method="post" enctype="multipart/form-data" class="w3-container w3-card-4 w3-light-grey">
                    <input type="hidden" name="id_admin" value="<?php echo $data['id_admin']; ?>">

                    <p>
                        <label>Username</label>
                        <input class="w3-input w3-border" type="text" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required>
                    </p>

                    <p>
                        <label>Nama Admin</label>
                        <input class="w3-input w3-border" type="text" name="nama_admin" value="<?php echo htmlspecialchars($data['nama_admin']); ?>" required>
                    </p>

                    <p>
                        <label>Alamat</label>
                        <input class="w3-input w3-border" type="text" name="alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>" required>
                    </p>

                    <p>
                        <label>no_hp</label>
                        <input class="w3-input w3-border" type="text" name="no_hp" value="<?php echo htmlspecialchars($data['no_hp']); ?>" required>
                    </p>

                    <p>
                        <label>email</label>
                        <input class="w3-input w3-border" type="text" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                    </p>

                    <p>
                        <label>Password</label>
                        <input class="w3-input w3-border" type="text" name="password" value="<?php echo htmlspecialchars($data['password']); ?>" required>
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
            echo "<p class='w3-red w3-padding'>ID Admin tidak ditemukan.</p>";
        }
        ?>
    </div>
</body>
</html>
