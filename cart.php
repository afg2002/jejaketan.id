<?php

ob_start();
include 'header.php';

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user'])) {
  // Redirect ke halaman login jika pengguna belum login
  header("Location: login.php");
  exit();
}

// Mendapatkan ID pengguna dari sesi
$userId = $_SESSION['user']['user_id'];
// var_dump($userId);

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
    $totalPrice = 0; // Inisialisasi total harga
?>

    <section class="section">
      <div class="container">
        <h1 class="title">Cart</h1>

        <div class="content">
          <table class="table is-fullwidth">
            <thead>
              <tr>
                <th>Product Image</th>
                <th>Product</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Loop melalui setiap item di keranjang -->
              <?php
              while ($row = mysqli_fetch_assoc($itemResult)) {
                $itemId = $row['cart_item_id'];
                $productName = $row['product_name'];
                $category = $row['category'];
                $price = $row['price'];
                $image = $row['image_url'];
                $quantity = $row['quantity'];
                $subtotal = $price * $quantity; // Menghitung subtotal harga per item
                $totalPrice += $subtotal; // Menghitung total harga keseluruhan
              ?>
                <tr>
                  <td><img src="<?php echo './product_image/' . $image; ?>" alt="Product Image" style="width: 80px;"></td>
                  <td><?php echo $productName; ?></td>
                  <td><?php echo $category; ?></td>
                  <td>
                    <input type="number" min="1" value="<?php echo $quantity; ?>" class="input quantity-input" data-item-id="<?php echo $itemId; ?>">
                  </td>
                  <td>Rp <?php echo number_format($price, 0, ',', '.'); ?></td>
                  <td>Rp <span class="subtotal" data-item-id="<?php echo $itemId; ?>"><?php echo number_format($subtotal, 0, ',', '.'); ?></span></td>
                  <td>
                    <a href="#" class="button is-danger is-small remove-from-cart" data-item-id="<?php echo $itemId; ?>">Remove</a>
                  </td>
                </tr>
              <?php
              }
              ?>
              <tr>
                <td colspan="5" style="text-align:right;"><strong>Total Price:</strong></td>
                <td colspan="2">Rp.<span id="total-price"><?php echo number_format($totalPrice, 0, ',', '.'); ?></span></td>
              </tr>
            </tbody>
          </table>
          <div class="is-pulled-right">
            <a href="checkout.php" class="button is-primary">Checkout</a>
          </div>
        </div>
      </div>
    </section>

<?php
  } else {
    // Jika keranjang belanja kosong
?>

    <section class="section">
      <div class="container">
        <h1 class="title">Cart</h1>

        <div class="content">
          <table class="table is-fullwidth">
            <thead>
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" style="text-align:center;">Your cart is empty.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

<?php
  }
} else {
  // Jika data keranjang tidak ditemukan
?>

  <section class="section">
    <div class="container">
      <h1 class="title">Cart</h1>

      <div class="content">
        <table class="table is-fullwidth">
          <thead>
            <tr>
              <th>Product</th>
              <th>Category</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Total Price</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="6" style="text-align:center;">Your cart is empty.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

<?php
}
include 'footer.php';

ob_end_flush();
?>

<script>
  $(document).ready(function() {
    // Menangani klik pada tombol remove
    $('.remove-from-cart').on('click', function(e) {
      e.preventDefault();
      var itemId = $(this).data('item-id');

      // Kirim permintaan Ajax untuk menghapus item dari keranjang
      $.ajax({
        url: 'remove-from-cart.php?id=' + itemId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            // Hapus baris item dari tabel
            $(e.target).closest('tr').remove();
            // Update total harga setelah menghapus item
            updateTotalPrice();
          } else {
            // Tampilkan pesan gagal jika item tidak dapat dihapus
            alert('Failed to remove the item from cart.');
          }
        },
        error: function() {
          // Tampilkan pesan gagal jika terjadi kesalahan Ajax
          alert('An error occurred while removing the item from cart.');
        }
      });
    });

    // Menangani perubahan jumlah kuantitas
    $('.quantity-input').on('change', function() {
      var itemId = $(this).data('item-id');
      var quantity = parseInt($(this).val());

      // Kirim permintaan Ajax untuk memperbarui kuantitas item di keranjang
      $.ajax({
        url: 'update-quantity.php?id=' + itemId + '&quantity=' + quantity,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            // Update subtotal harga setelah mengubah kuantitas
            updateSubtotalPrice(itemId, quantity);
            // Update total harga setelah mengubah kuantitas
            updateTotalPrice();
          } else {
            // Tampilkan pesan gagal jika kuantitas tidak dapat diperbarui
            alert('Failed to update the quantity.');
          }
        },
        error: function() {
          // Tampilkan pesan gagal jika terjadi kesalahan Ajax
          alert('An error occurred while updating the quantity.');
        }
      });
    });

    // Fungsi untuk memperbarui subtotal harga item
    function updateSubtotalPrice(itemId, quantity) {
      var price = parseFloat($('[data-item-id="' + itemId + '"]').closest('tr').find('td:eq(4)').text().replace('Rp', '').replace(/\./g, '').replace(',', '.'));
      var subtotal = price * quantity;
      $('[data-item-id="' + itemId + '"]').closest('tr').find('td:eq(5)').text('Rp ' + subtotal.toLocaleString('id-ID'));
    }

    // Fungsi untuk memperbarui total harga keseluruhan
    function updateTotalPrice() {
      var totalPrice = 0;
      $('.quantity-input').each(function() {
        var itemId = $(this).data('item-id');
        var quantity = parseInt($(this).val());
        var price = parseFloat($('[data-item-id="' + itemId + '"]').closest('tr').find('td:eq(4)').text().replace('Rp', '').replace(/\./g, '').replace(',', '.'));
        var subtotal = price * quantity;
        totalPrice += subtotal;
      });
      $('#total-price').text(totalPrice.toLocaleString('id-ID'));
    }
  });
</script>
