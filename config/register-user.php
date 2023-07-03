<?php
include 'db.php';

// Data yang diterima dari form registrasi
$fullName = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$address = $_POST['address'];
$phoneNumber = $_POST['phone_number'];
$role = 'user';

// Query untuk memeriksa keberadaan email
$checkQuery = "SELECT * FROM User WHERE email = '$email'";
$result = mysqli_query($conn, $checkQuery);

// Cek jumlah baris yang dikembalikan oleh query SELECT
if (mysqli_num_rows($result) > 0) {
    // Jika email sudah terdaftar, tampilkan pesan peringatan
    echo "<script>alert('Email sudah terdaftar');</script>";
    header('location:../index.php');
} else {
    // Jika email belum terdaftar, jalankan query INSERT dengan password yang di-hash
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insertQuery = "INSERT INTO User (full_name, email, password, address, phone_number, role,created_at) VALUES ('$fullName', '$email', '$hashedPassword', '$address', '$phoneNumber', '$role',NOW())";
    
    if (mysqli_query($conn, $insertQuery)) {
        // Registrasi berhasil
        header('location:../index.php');
    } else {
        // Error saat menjalankan query INSERT
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

// Tutup koneksi database
mysqli_close($conn);
?>