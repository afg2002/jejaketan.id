<?php
include '../config/db.php';

// Ambil nilai form yang ditambahkan
$productName = mysqli_real_escape_string($conn, $_POST['product_name']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$price = mysqli_real_escape_string($conn, $_POST['price']);
$category = mysqli_real_escape_string($conn, $_POST['category']);
$isFeatured = mysqli_real_escape_string($conn, $_POST['is_featured']);

// Ambil file gambar yang diunggah
$image = $_FILES['image'];
$imagePath = '../product_image/'; // Ubah dengan path sesuai dengan kebutuhan Anda

// Pindahkan file gambar ke direktori yang ditentukan
$imageFileName = uniqid() . '_' . $image['name'];
$imageFilePath = $imagePath . $imageFileName;
move_uploaded_file($image['tmp_name'], $imageFilePath);

// Tambahkan data produk ke database
$query = "INSERT INTO Product (product_name, description, price, image_url, category, is_featured) VALUES ('$productName', '$description', '$price', '$imageFileName', '$category', '$isFeatured')";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "<script>alert('Berhasil ditambahkan')</script>";
    header("location:../admin/products.php");
} else {
    echo "<script>alert('Gagal ditambahkan')</script>";
}

?>
