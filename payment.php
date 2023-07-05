<?php
include 'header.php';

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user'])) {
  // Jika pengguna belum login, arahkan ke halaman login
  echo "<script>location.href=index.php</script>";
  
  exit();
}

// Periksa apakah order_id tersedia dalam parameter URL
if (!isset($_GET['order_id'])) {
  // Jika order_id tidak tersedia, arahkan ke halaman lain
  header("Location: index.php");
  exit();
}

// Mendapatkan ID pengguna dari sesi
$userId = $_SESSION['user']['user_id'];

// Mendapatkan order_id dari parameter URL
$orderId = $_GET['order_id'];

// Periksa apakah order_id yang dimiliki oleh pengguna yang saat ini login
$checkOrderQuery = "SELECT * FROM Orders WHERE order_id = $orderId AND user_id = $userId";
$checkOrderResult = mysqli_query($conn, $checkOrderQuery);

if ($checkOrderResult && mysqli_num_rows($checkOrderResult) > 0) {
  // Mendapatkan detail pesanan dari tabel "Orders"
  $orderQuery = "SELECT * FROM Orders WHERE order_id = $orderId";
  $orderResult = mysqli_query($conn, $orderQuery);
  $order = mysqli_fetch_assoc($orderResult);

  // Mendapatkan detail pengiriman dari tabel "ShippingDetails"
  $shippingQuery = "SELECT * FROM ShippingDetails WHERE order_id = $orderId";
  $shippingResult = mysqli_query($conn, $shippingQuery);
  $shipping = mysqli_fetch_assoc($shippingResult);

  // Mendapatkan detail metode pembayaran dari tabel "PaymentMethod"
  $paymentQuery = "SELECT * FROM PaymentMethod WHERE order_id = $orderId";
  $paymentResult = mysqli_query($conn, $paymentQuery);
  $payment = mysqli_fetch_assoc($paymentResult);

  // Mendapatkan detail item pesanan dari tabel "OrderItem"
  $itemQuery = "SELECT oi.*, p.product_name, p.price FROM OrderItem oi
                INNER JOIN Product p ON oi.product_id = p.product_id
                WHERE oi.order_id = $orderId";
  $itemResult = mysqli_query($conn, $itemQuery);

  ?>

  <body>
    <section class="section">
      <div class="container">
        <h1 class="title">Payment</h1>

        <h2 class="subtitle">User Details</h2>
        <p><strong>Full Name:</strong> <?php echo $shipping['full_name']; ?></p>
        <p><strong>Email:</strong> <?php echo $shipping['email']; ?></p>
        <p><strong>Address:</strong> <?php echo $shipping['address']; ?></p>
        <p><strong>Phone Number:</strong> <?php echo $shipping['phone_number']; ?></p>

        <h2 class="subtitle">Order Details</h2>
        <p><strong>Order ID:</strong> <?php echo $orderId; ?></p>
        <p><strong>Payment Method:</strong> <?php echo $payment['payment_method']; ?></p>

        <h3 class="subtitle">Order Items</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while ($item = mysqli_fetch_assoc($itemResult)) {
              echo "<tr>";
              echo "<td>" . $item['product_name'] . "</td>";
              echo "<td>" . $item['quantity'] . "</td>";
              echo "<td>" . $item['price'] . "</td>";
              echo "<td>" . $item['subtotal'] . "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>

        <h2 class="subtitle">Upload Payment Proof</h2>
        <form action="upload-payment.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
          <div class="field">
            <div class="file is-primary has-name">
              <label class="file-label">
                <input class="file-input" type="file" name="payment_proof" required>
                <span class="file-cta">
                  <span class="file-icon">
                    <i class="fas fa-upload"></i>
                  </span>
                  <span class="file-label">
                    Choose a file…
                  </span>
                </span>
                <span class="file-name">
                  No file selected
                </span>
              </label>
            </div>
          </div>
          <div class="field">
            <div class="control">
              <input class="button is-primary" type="submit" value="Upload">
            </div>
          </div>
        </form>
      </div>
    </section>
<?php 

include 'footer.php';
?>
    <script>
      // Update file input label text
      const fileInput = $('.file-input');
      const fileLabel = $('.file-name');

      fileInput.on('change', function(event) {
        const fileName = event.target.files[0].name;
        fileLabel.text(fileName);
      });
    </script>
  </body>
  </html>

  <?php
} else {
  // Jika order_id tidak sesuai dengan pengguna yang saat ini login, arahkan ke halaman lain
  header("Location: index.php");
  exit();
}

?>
