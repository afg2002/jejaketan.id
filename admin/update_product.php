<?php
ob_start();
include '../header.php';

// Periksa apakah pengguna sudah login dan merupakan admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Jika bukan admin, redirect ke halaman lain atau tampilkan pesan error
    header('Location: index.php');
    exit();
}

// Periksa apakah parameter 'id' ada dalam URL
if (!isset($_GET['id'])) {
    // Jika tidak ada, redirect ke halaman lain atau tampilkan pesan error
    header('Location: products.php');
    exit();
}

$productId = $_GET['id'];

// Periksa apakah produk dengan ID tersebut ada dalam database
$query = "SELECT * FROM Product WHERE product_id = $productId";
$result = mysqli_query($conn, $query);

// Periksa apakah query berhasil dijalankan dan ada hasil
if (!$result || mysqli_num_rows($result) === 0) {
    // Jika query gagal atau tidak ada hasil, redirect ke halaman lain atau tampilkan pesan error
    header('Location: products.php');
    exit();
}

$row = mysqli_fetch_assoc($result);

// Inisialisasi variabel untuk menyimpan pesan sukses atau error
$message = '';

// Periksa apakah form update telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $productName = $_POST['product_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'];
    $isFeatured = $_POST['is_featured'];

    // Proses update ke database
    $query = "UPDATE Product SET 
    product_name = '" . mysqli_real_escape_string($conn, $productName) . "',
    category = '" . mysqli_real_escape_string($conn, $category) . "',
    description = '" . mysqli_real_escape_string($conn, $description) . "',
    price = $price,
    is_featured = '$isFeatured',
    updated_at = NOW()";

    // Proses update gambar jika ada perubahan
    if ($image['size'] > 0) {
        $imagePath = '../product_image/'; // Ubah path sesuai dengan kebutuhan Anda

        // Hapus gambar lama jika ada
        if (!empty($row['image_url'])) {
            unlink($imagePath . $row['image_url']);
        }

        // Upload gambar baru
        $imageFileName = uniqid() . '_' . $image['name'];
        $imageURL = $imagePath . $imageFileName;
        if (move_uploaded_file($image['tmp_name'], $imageURL)) {
            // Tambahkan nama gambar baru ke query update
            $query .= ", image_url = '$imageFileName'";
        } else {
            $message = 'Gagal mengupload gambar.';
        }
    }

    $query .= " WHERE product_id = $productId";

    $result = mysqli_query($conn, $query);

    // Periksa apakah query update berhasil dijalankan
    if ($result) {
        // Redirect ke halaman produk setelah berhasil diupdate
        header('Location: products.php');
        exit();
    } else {
        $message = 'Terjadi kesalahan saat mengupdate produk: ' . mysqli_error($conn);
    }
}
ob_end_flush();
?>

<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Update Product</h1>

            <?php if (!empty($message)) : ?>
                <div class="notification is-danger">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="field">
                    <label class="label">Product Name</label>
                    <div class="control">
                        <input class="input" type="text" name="product_name" value="<?php echo $row['product_name']; ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Category</label>
                    <div class="control">
                        <input class="input" type="text" name="category" value="<?php echo $row['category']; ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Description</label>
                    <div class="control">
                        <textarea class="textarea" name="description" required><?php echo $row['description']; ?></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Price</label>
                    <div class="control">
                        <input class="input" type="number" name="price" value="<?php echo $row['price']; ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Is Featured</label>
                    <div class="control">
                        <label class="radio">
                            <input type="radio" name="is_featured" value="Y" <?php if ($row['is_featured'] === 'Y') echo 'checked'; ?>>
                            Yes
                        </label>
                        <label class="radio">
                            <input type="radio" name="is_featured" value="N" <?php if ($row['is_featured'] === 'N') echo 'checked'; ?>>
                            No
                        </label>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Image</label>
                    <img width="80" height="80" src="../product_image/<?php echo $row['image_url']; ?>">
                    <div class="control">
                        <input class="input" type="file" name="image">
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <button class="button is-primary" type="submit">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</body>

<?php include '../footer.php'; ?>
