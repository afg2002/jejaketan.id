<?php
include '../header.php';

// Periksa apakah pengguna sudah login dan merupakan admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Jika bukan admin, redirect ke halaman lain atau tampilkan pesan error
    header('Location: index.php');
    exit();
}

$query = "SELECT * FROM Product";
$result = mysqli_query($conn, $query);

// Periksa apakah query berhasil dijalankan
if (!$result) {
    // Jika query gagal, tampilkan pesan error
    echo 'Error: ' . mysqli_error($conn);
    exit();
}
?>

<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Admin - Data Produk</h1>

            <!-- Form Tambah Produk -->
            <form method="POST" class="mb-4" enctype="multipart/form-data" action="admin-add-prod.php">
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Nama Produk</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" type="text" name="product_name" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Category</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <select class="input" name="category">
                                    <option selected disabled> Pilih Category </option>
                                    <option> Men's Jacket </option>
                                    <option> Women's Jacket </option>
                                    <option> Kids' Jacket </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Deskripsi</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <textarea class="textarea" name="description" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Harga</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" type="text" name="price" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Gambar</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" type="file" name="image" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Featured</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="is_featured" value="Y">
                                    Ya
                                </label>
                                <label class="radio">
                                    <input type="radio" name="is_featured" value="N" checked>
                                    Tidak
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label"></div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <button class="button is-primary" type="submit">Tambah Produk</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Tabel Data Produk -->
            <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Category</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th>Featured</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Tampilkan data produk
                    while ($row = mysqli_fetch_assoc($result)) {
                        $featured = $row['is_featured'] == 'Y' ? 'Yes' : 'No';
                        echo '
                        <tr>
                            <td>' . $row['product_id'] . '</td>
                            <td>' . $row['product_name'] . '</td>
                            <td>' . $row['category'] . '</td>
                            <td>' . $row['description'] . '</td>
                            <td>' . 'Rp.' . number_format($row['price'], 0, null, '.') . '</td>
                            <td><img src="../product_image/' . $row['image_url'] . '" width="80" height="80"></td>
                            <td>' . $featured . '</td>
                            <td>
                                <a style="width:50%;" class="button is-link is-small" href="update_product.php?id=' . $row['product_id'] . '">Update</a>
                                <a style="width:50%;" class="button is-danger is-small" href="delete_product.php?id=' . $row['product_id'] . '">Delete</a>
                            </td>
                        </tr>
                        ';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php include '../footer.php'  ?>
