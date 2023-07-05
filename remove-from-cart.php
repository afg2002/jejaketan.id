<?php

// Periksa apakah permintaan adalah permintaan AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
  // Jika bukan permintaan AJAX, alihkan pengguna ke halaman yang sesuai
  header("Location: index.php");
  exit();
}

// Periksa apakah ID item yang akan dihapus diberikan
if (!isset($_GET['id'])) {
  $response = array('success' => false, 'message' => 'Item ID is missing.');
  echo json_encode($response);
  exit();
}

// Dapatkan ID item yang akan dihapus
$itemId = $_GET['id'];

// Sisipkan file konfigurasi database
require_once 'config/db.php';

// Periksa apakah pengguna telah login
session_start();
if (!isset($_SESSION['user'])) {
  $response = array('success' => false, 'message' => 'You are not logged in.');
  echo json_encode($response);
  exit();
}

// Dapatkan ID pengguna dari sesi
$userId = $_SESSION['user']['user_id'];

// Query untuk menghapus item dari keranjang berdasarkan ID item dan ID pengguna
$query = "DELETE FROM CartItem WHERE cart_item_id = $itemId AND cart_id = (SELECT cart_id FROM Cart WHERE user_id = $userId)";
$result = mysqli_query($conn, $query);

// Periksa apakah penghapusan berhasil
if ($result) {
  $response = array('success' => true, 'message' => 'Item removed from cart successfully.');
} else {
  $response = array('success' => false, 'message' => 'Failed to remove the item from cart.');
}

// Mengembalikan respons dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);

?>
