<?php
include 'config/db.php';
session_start();


// Periksa apakah pengguna telah login
if (!isset($_SESSION['user'])) {
  // Jika pengguna belum login, kirimkan pesan error
  $response = array(
    'success' => false,
    'message' => 'Please login to place an order.'
  );
  echo json_encode($response);
  
  exit();
}

// Periksa apakah metode pembayaran sudah dipilih
if (!isset($_POST['payment_method'])) {
  // Jika metode pembayaran belum dipilih, kirimkan pesan error
  $response = array(
    'success' => false,
    'message' => 'Payment method not selected.'
  );
  echo json_encode($response);
  exit();
}

// Mendapatkan ID pengguna dari sesi
$userId = $_SESSION['user']['user_id'];

// Mendapatkan cart_id dari database berdasarkan user_id
$getCartIdQuery = "SELECT cart_id FROM Cart WHERE user_id = $userId";
$getCartIdResult = mysqli_query($conn, $getCartIdQuery);

if ($getCartIdResult && mysqli_num_rows($getCartIdResult) > 0) {
  $row = mysqli_fetch_assoc($getCartIdResult);
  $cartId = $row['cart_id'];

  // Simpan data pesanan ke dalam tabel "Orders"
  $insertOrderQuery = "INSERT INTO Orders (user_id) VALUES ($userId)";
  if (mysqli_query($conn, $insertOrderQuery)) {
    $orderId = mysqli_insert_id($conn);

    // Simpan data detail pesanan ke dalam tabel "OrderItem"
    $itemQuery = "SELECT c.*, p.price FROM CartItem c
                  INNER JOIN Product p ON c.product_id = p.product_id
                  WHERE c.cart_id = $cartId";
    $itemResult = mysqli_query($conn, $itemQuery);

    while ($row = mysqli_fetch_assoc($itemResult)) {
      $productId = $row['product_id'];
      $quantity = $row['quantity'];
      $subtotal = $row['price'] * $quantity;

      $insertOrderItemQuery = "INSERT INTO OrderItem (order_id, product_id, quantity, subtotal) VALUES ($orderId, $productId, $quantity, $subtotal)";
      mysqli_query($conn, $insertOrderItemQuery);
    }

    // Simpan data pengiriman ke dalam tabel "ShippingDetails"
    $fullName = $_SESSION['user']['full_name'];
    $email = $_SESSION['user']['email'];
    $address = $_SESSION['user']['address'];
    $phoneNumber = $_SESSION['user']['phone_number'];

    $insertShippingDetailsQuery = "INSERT INTO ShippingDetails (order_id, full_name, email, address, phone_number) VALUES ($orderId, '$fullName', '$email', '$address', '$phoneNumber')";
    mysqli_query($conn, $insertShippingDetailsQuery);

    // Simpan data metode pembayaran ke dalam tabel "PaymentMethod"
    $paymentMethod = $_POST['payment_method'];

    $insertPaymentMethodQuery = "INSERT INTO PaymentMethod (order_id, payment_method) VALUES ($orderId, '$paymentMethod')";
    mysqli_query($conn, $insertPaymentMethodQuery);

    // Hapus keranjang belanja setelah berhasil melakukan checkout
    $deleteCartItemsQuery = "DELETE FROM CartItem WHERE cart_id = $cartId";
    mysqli_query($conn, $deleteCartItemsQuery);

    // Kirimkan respons berhasil
    $response = array(
      'success' => true,
      'message' => 'Order placed successfully.',
      'order_id' => $orderId
    );
    echo json_encode($response);
    exit();
  } else {
    // Jika terjadi kesalahan saat menyimpan pesanan ke database, kirimkan pesan error
    $response = array(
      'success' => false,
      'message' => 'Error placing order.'
    );
    echo json_encode($response);
    exit();
  }
} else {
  // Jika cart_id tidak ditemukan, kirimkan pesan error
  $response = array(
    'success' => false,
    'message' => 'Cart not found.'
  );
  echo json_encode($response);
  exit();
}
?>
