<?php
include '../config/db.php';

// Periksa apakah parameter 'id' ada dalam URL
if (!isset($_GET['id'])) {
    // Jika tidak ada, redirect ke halaman lain atau tampilkan pesan error
    header('Location: products.php');
    exit();
}

$productId = $_GET['id'];

// Hapus data produk dari database
$query = "SELECT * FROM Product WHERE product_id = $productId";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    
    // Hapus file gambar terkait jika ada
    $imagePath = '../product_image/'; // Ubah dengan path sesuai dengan kebutuhan Anda
    $imageURL = $imagePath . $row['image_url'];
    
    if (!empty($row['image_url']) && file_exists($imageURL)) {
        unlink($imageURL);
    }
    
    // Hapus data produk dari database
    $deleteQuery = "DELETE FROM Product WHERE product_id = $productId";
    $deleteResult = mysqli_query($conn, $deleteQuery);
    
    if ($deleteResult) {
        echo "<script>alert('Berhasil menghapus produk')</script>";
    } else {
        echo "<script>alert('Gagal menghapus produk')</script>";
    }
    
    header("location: products.php");
} else {
    echo "<script>alert('Gagal menghapus produk')</script>";
    header("location: products.php");
}
?>
