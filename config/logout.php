<?php
session_start();

// Hapus semua data sesi
session_destroy();

// Redirect ke halaman login atau halaman lain yang Anda inginkan setelah logout
header('Location: ../index.php');
exit();
?>
