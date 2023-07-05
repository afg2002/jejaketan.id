<?php
include 'header.php';
ob_start();
// Periksa apakah pengguna telah login
if (!isset($_SESSION['user'])) {
  // Jika pengguna belum login, arahkan ke halaman login
  header("Location: login.php");
  exit();
}

// Periksa apakah order_id tersedia dalam parameter POST
if (!isset($_POST['order_id'])) {
  // Jika order_id tidak tersedia, arahkan ke halaman lain
  header("Location: index.php");
  exit();
}

// Mendapatkan ID pengguna dari sesi
$userId = $_SESSION['user']['user_id'];

// Mendapatkan order_id dari parameter POST
$orderId = $_POST['order_id'];

// Periksa apakah order_id yang dimiliki oleh pengguna yang saat ini login
$checkOrderQuery = "SELECT * FROM Orders WHERE order_id = $orderId AND user_id = $userId";
$checkOrderResult = mysqli_query($conn, $checkOrderQuery);

if ($checkOrderResult && mysqli_num_rows($checkOrderResult) > 0) {
  // Periksa apakah file bukti pembayaran telah diunggah
  if (isset($_FILES['payment_proof'])) {
    $file = $_FILES['payment_proof'];

    // Mendapatkan informasi file
    $fileName = $file['name'];
    $fileTmpPath = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Periksa apakah tidak ada error saat mengunggah file
    if ($fileError === UPLOAD_ERR_OK) {
      // Mendapatkan ekstensi file
      $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

      // Definisikan direktori penyimpanan file
      $uploadDir = './payment_proof/';
      // Menghasilkan nama file unik untuk menghindari bentrok
      $uniqueFileName = uniqid('proof_') . '.' . $fileExt;
      // Menggabungkan direktori penyimpanan dengan nama file unik
      $uploadPath = $uploadDir . $uniqueFileName;

      // Pindahkan file ke direktori penyimpanan
      if (move_uploaded_file($fileTmpPath, $uploadPath)) {
        // Insert informasi pembayaran ke tabel "Payment"
        $insertPaymentQuery = "INSERT INTO Payment (order_id, image_url) VALUES ($orderId, '$uniqueFileName')";
        $insertPaymentResult = mysqli_query($conn, $insertPaymentQuery);

        if ($insertPaymentResult) {
          $updateStatusOrder = "UPDATE Orders SET is_paid = 'Y' WHERE order_id = $orderId ";
          $updateStatusOrderResult = mysqli_query($conn, $updateStatusOrder);

          if($updateStatusOrderResult){
            header("Location: order-history.php");
          }else{
            echo "Berhasil memasukan informasi pembayaran tetapi gagal dalam mengupdate status order.";
          }
          // Redirect ke halaman berhasil upload dengan mengirimkan ID pembayaran
          header("Location: order-history.php");
          exit();
        } else {
          // Jika gagal memasukkan informasi pembayaran ke tabel
          echo "Failed to insert payment information.";
        }
      } else {
        // Jika gagal memindahkan file ke direktori penyimpanan
        echo "Failed to move uploaded file.";
      }
    } else {
      // Jika terjadi error saat mengunggah file
      echo "Error uploading file: " . $fileError;
    }
  } else {
    // Jika file bukti pembayaran tidak tersedia
    echo "Payment proof file is missing.";
  }
} else {
  // Jika order_id tidak sesuai dengan pengguna yang saat ini login
  header("Location: index.php");
  exit();
}

// ob_end_flush();
include 'footer.php';
?>
