<?php
include 'header.php';

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user'])) {
  // Redirect ke halaman login jika pengguna belum login
  echo "<script>location.href=index.php</script>";
  exit();
}

// Mendapatkan ID pengguna dari sesi
$userId = $_SESSION['user']['user_id'];

// Query untuk mendapatkan data keranjang berdasarkan ID pengguna
$query = "SELECT * FROM Cart WHERE user_id = $userId";
$result = mysqli_query($conn, $query);

// Periksa apakah data keranjang ditemukan
if (mysqli_num_rows($result) > 0) {
  $cart = mysqli_fetch_assoc($result);
  $cartId = $cart['cart_id'];

  // Query untuk mendapatkan data item keranjang berdasarkan ID keranjang
  $itemQuery = "SELECT c.*, p.product_name, p.category, p.price, p.image_url FROM CartItem c
                INNER JOIN Product p ON c.product_id = p.product_id
                WHERE c.cart_id = $cartId";
  $itemResult = mysqli_query($conn, $itemQuery);

  // Periksa apakah ada item di keranjang
  if (mysqli_num_rows($itemResult) > 0) {
    ?>
    <section class="section">
      <div class="container">
        <h1 class="title">Checkout</h1>

        <div class="content">
          <h2 class="subtitle">Order Summary</h2>
          <table class="table is-fullwidth">
            <thead>
              <tr>
                <th>Product Image</th>
                <th>Product</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
              </tr>
            </thead>
            <tbody>
              <!-- Loop melalui setiap item di keranjang -->
              <?php
              while ($row = mysqli_fetch_assoc($itemResult)) {
                $productName = $row['product_name'];
                $category = $row['category'];
                $price = $row['price'];
                $image = $row['image_url'];
                $quantity = $row['quantity'];
                $subtotal = $price * $quantity; // Menghitung subtotal harga per item
                ?>
                <tr>
                  <td><img src="<?php echo './product_image/' . $image; ?>" alt="Product Image" style="width: 80px;"></td>
                  <td><?php echo $productName; ?></td>
                  <td><?php echo $category; ?></td>
                  <td><?php echo $quantity; ?></td>
                  <td>Rp <?php echo number_format($price, 0, ',', '.'); ?></td>
                  <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>

          <h2 class="subtitle">Shipping Details</h2>
          <p>Edit the shipping details on your <a href="<?php BASE_URL ?>profile.php">profile</a>.</p>
          <table class="table is-fullwidth">
            <tbody>
              <tr>
                <td><strong>Name:</strong></td>
                <td><?php echo $_SESSION['user']['full_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Email:</strong></td>
                <td><?php echo $_SESSION['user']['email']; ?></td>
              </tr>
              <tr>
                <td><strong>Address:</strong></td>
                <td><?php echo $_SESSION['user']['address']; ?></td>
              </tr>
              <tr>
                <td><strong>Phone Number:</strong></td>
                <td><?php echo $_SESSION['user']['phone_number']; ?></td>
              </tr>
            </tbody>
          </table>

          <h2 class="subtitle">Payment Method</h2>
          <div class="field">
            <label class="label">Select Payment Method:</label>
            <div class="control">
              <div class="select">
                <select id="paymentMethodSelect">
                <option  disabled selected >== Pilih Metode Pembayaran ==</option>
                  <option value="bank_transfer">Bank Transfer</option>
                  <option value="e_wallet">E-Wallet</option>
                </select>
              </div>
            </div>
          </div>

          <div id="bankTransferContainer">
            <div class="field">
              <label class="label">Select Bank:</label>
              <div class="control">
                <div class="select">
                  <select id="bankSelect" name="payment_method">
                    <option  disabled selected >== Pilih Metode Pembayaran ==</option>
                    <option value="bank_bca">Bank BCA</option>
                    <option value="bank_bni">Bank BNI</option>
                    <option value="bank_mandiri">Bank Mandiri</option>
                    <!-- Tambahkan bank-bank Indonesia lainnya sesuai kebutuhan -->
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div id="eWalletContainer">
            <div class="field">
              <label class="label">Select E-Wallet:</label>
              <div class="control">
                <div class="select">
                  <select id="eWalletSelect" name="payment_method">
                  <option  disabled selected >== Pilih Metode Pembayaran ==</option>
                    <option value="ewallet_gopay">GoPay</option>
                    <option value="ewallet_ovo">OVO</option>
                    <option value="ewallet_dana">DANA</option>
                    <!-- Tambahkan e-wallet Indonesia lainnya sesuai kebutuhan -->
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="is-pulled-right">
            <a id="placeOrderButton" class="button is-primary">Place Order</a>
          </div>
        </div>
      </div>
    </section>
    <?php
    include 'footer.php';
    ?>
    <script>
      $(document).ready(function() {
        // Tangkap elemen yang dibutuhkan
        var paymentMethodSelect = $("#paymentMethodSelect");
        var bankTransferContainer = $("#bankTransferContainer");
        var eWalletContainer = $("#eWalletContainer");

        // Saat halaman dimuat, sembunyikan formulir bank transfer dan e-wallet
        bankTransferContainer.hide();
        eWalletContainer.hide();
        var selectedPaymentMethod;
        // Ketika opsi metode pembayaran berubah
        paymentMethodSelect.change(function() {
          selectedPaymentMethod = $(this).val();

          // Semua formulir disembunyikan
          bankTransferContainer.hide();
          eWalletContainer.hide();

          // Tampilkan formulir sesuai metode pembayaran yang dipilih
          if (selectedPaymentMethod === "bank_transfer") {
            bankTransferContainer.show();
          } else if (selectedPaymentMethod === "e_wallet") {
            eWalletContainer.show();
          }
        });

        // Tangkap elemen yang dibutuhkan
        var placeOrderButton = $("#placeOrderButton");

        // Ketika tombol "Place Order" diklik
        placeOrderButton.click(function(e) {
          e.preventDefault();
          console.log(selectedPaymentMethod);

          var paymentMethod;

          // Mengambil nilai metode pembayaran yang dipilih
          if (selectedPaymentMethod === "bank_transfer") {
            paymentMethod = $("#bankSelect").val();
          } else if (selectedPaymentMethod === "e_wallet") {
            paymentMethod = $("#eWalletSelect").val();
          }

          // Validasi pilihan metode pembayaran
          if (selectedPaymentMethod === null || selectedPaymentMethod === "" || paymentMethod === null || paymentMethod === "") {
            alert("Please select a payment method.");
            return;
          }


          // Buat objek data untuk dikirim melalui AJAX
          var data = {
            payment_method: paymentMethod
          };

          console.log(data);

          // Kirim permintaan AJAX
          $.ajax({
            url: "order.php",
            type: "POST",
            data: data,
            dataType: "json",
            success: function(response) {
              // Periksa apakah pesanan berhasil ditempatkan
              if (response.success) {
                 // Redirect ke halaman payment.php dengan mengirimkan ID pesanan
                  window.location.href = "payment.php?order_id=" + response.order_id;
              } else {
                // Tampilkan pesan error jika terjadi kesalahan
                alert("Failed to place order. Please try again.");
                console.log(response);
              }
            },
            error: function(response) {
              // Tampilkan pesan error jika terjadi kesalahan
              console.log(response);
            }
          });
        });
      });
    </script>

  <?php
  } else {
    // Jika keranjang belanja kosong
    echo "Cart is empty.";
  }
} else {
  // Jika pengguna belum login
  echo "Please login to access this page.";
}


?>
