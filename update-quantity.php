<?php
include 'config/db.php';

// Periksa apakah ada parameter id dan quantity yang diberikan
if (isset($_GET['id']) && isset($_GET['quantity'])) {
  // Ambil nilai id dan quantity dari parameter
  $itemId = $_GET['id'];
  $quantity = $_GET['quantity'];

  // Lakukan validasi jumlah kuantitas
  if ($quantity > 0) {
    // Update kuantitas item dalam database


    // Query untuk mengupdate kuantitas item di tabel CartItem
    $updateQuery = "UPDATE CartItem SET quantity = $quantity WHERE cart_item_id = $itemId";

    // Eksekusi query update
    if (mysqli_query($conn, $updateQuery)) {
      // Kuantitas berhasil diperbarui
      $response = array('success' => true);
    } else {
      // Gagal memperbarui kuantitas item
      $response = array('success' => false);
    }
} else {
    // Jumlah kuantitas tidak valid (kurang dari atau sama dengan 0)
    $response = array('success' => false, 'error' => mysqli_error($conn));
  }
} else {
  // Parameter id dan quantity tidak diberikan
  $response = array('success' => false);
}

// Mengembalikan respons dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
