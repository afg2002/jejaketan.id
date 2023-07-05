<?php
session_start();
include './config/db.php'; // Include your database connection file

// Periksa apakah ada ID produk yang diberikan melalui metode POST
if (isset($_POST['productId'])) {
  $productId = $_POST['productId'];
  $quantity = NULL;

  // Query untuk memeriksa keberadaan produk berdasarkan ID produk
  $query = "SELECT * FROM Product WHERE product_id = $productId";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Produk ditemukan, lanjutkan dengan proses penambahan ke keranjang

    // Periksa apakah ada keranjang yang ada untuk pengguna
    $userId = $_SESSION['user']['user_id']; // Ganti dengan metode yang sesuai untuk mendapatkan ID pengguna saat ini
    $cartQuery = "SELECT * FROM Cart WHERE user_id = $userId";
    $cartResult = mysqli_query($conn, $cartQuery);

    if (mysqli_num_rows($cartResult) > 0) {
      // Keranjang pengguna sudah ada, dapatkan ID keranjang
      $cartRow = mysqli_fetch_assoc($cartResult);
      $cartId = $cartRow['cart_id'];
    } else {
      // Keranjang pengguna belum ada, buat keranjang baru
      $cartInsertQuery = "INSERT INTO Cart (user_id) VALUES ($userId)";
      mysqli_query($conn, $cartInsertQuery);

      // Dapatkan ID keranjang yang baru dibuat
      $cartId = mysqli_insert_id($conn);
    }

    // Periksa apakah item sudah ada di keranjang
    $cartItemQuery = "SELECT * FROM CartItem WHERE cart_id = $cartId AND product_id = $productId";
    $cartItemResult = mysqli_query($conn, $cartItemQuery);

    if (mysqli_num_rows($cartItemResult) > 0) {
      // Item sudah ada di keranjang, tambahkan jumlahnya
      $cartItemRow = mysqli_fetch_assoc($cartItemResult);
      $cartItemId = $cartItemRow['cart_item_id'];
      $quantity = $cartItemRow['quantity'] + 1;

      $updateQuantityQuery = "UPDATE CartItem SET quantity = $quantity WHERE cart_item_id = $cartItemId";
      mysqli_query($conn, $updateQuantityQuery);
    } else {
      // Item belum ada di keranjang, tambahkan item baru
      $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Set a default quantity of 1 if not provided

      $insertItemQuery = "INSERT INTO CartItem (cart_id, product_id, quantity) VALUES ($cartId, $productId, $quantity)";
    mysqli_query($conn, $insertItemQuery);
    }

    // Respon sukses
    echo "Product added to cart successfully.";
  } else {
    // Produk tidak ditemukan
    echo "Invalid product ID.";
  }
} else {
  // Jika ID produk tidak diberikan
  echo "Invalid product ID.";
}
?>
