<?php

include 'db.php';

session_start();

// Data yang diterima dari form login
$email = $_POST['email'];
$password = $_POST['password'];

// Query untuk memeriksa keberadaan pengguna dengan email yang sesuai
$query = "SELECT * FROM User WHERE email = '$email'";
$result = mysqli_query($conn, $query);

// Cek jumlah baris yang dikembalikan oleh query SELECT
if (mysqli_num_rows($result) > 0) {
    // Pengguna ditemukan, cek password
    $user = mysqli_fetch_assoc($result);
    
    // Verifikasi hash password
    if (password_verify($password, $user['password'])) {
        // Password cocok, login berhasil
        $_SESSION['user'] = $user;
        
        // Redirect ke halaman sesuai dengan peran pengguna
        if ($user['role'] == 'admin') {
            header('location: ../index.php'); 
        } elseif ($user['role'] == 'user') {
            header('location: ../index.php');
        } else {
            // Peran pengguna tidak valid
            echo "<script>alert('Peran pengguna tidak valid');</script>";
            echo '<script>location.href = "../index.php"</script>';
            // header('location.reload()');
        }
    } else {
        // Password tidak cocok, tampilkan pesan peringatan
        echo "<script>alert('Email atau password salah');</script>";
        echo '<script>location.href = "../index.php"</script>';
        // header('location.reload()');
    }
} else {
    // Pengguna tidak ditemukan, tampilkan pesan peringatan
    echo "<script>alert('Email atau password salah');</script>";
    echo '<script>location.href = "../index.php"</script>';
    // header('location.reload()');
}

// Tutup koneksi database
mysqli_close($conn);

?>
