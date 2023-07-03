<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Di file config/db.php atau file lain yang digunakan sebagai konfigurasi
define('BASE_URL', 'http://localhost/jejaketan/'); // Sesuaikan dengan URL dan direktori aplikasi Anda
define('IMAGE_URL','../product_image');


// Konfigurasi koneksi database
$host = 'localhost';
$dbname = 'jejaketan';
$username = 'root';
$password = '';

// Membuat koneksi ke database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Periksa apakah koneksi berhasil
if (mysqli_connect_errno()) {
     echo "Koneksi database gagal: " . mysqli_connect_error();
}

// Tampilkan pesan berhasil terhubung ke database
// echo "Berhasil terhubung ke database";

// Tutup koneksi database
// mysqli_close($conn);
?>
