<?php include 'header.php'; ?>

<section class="section">
  <div class="container">
    <h1 class="title">Product Details</h1>

    <?php
    // Periksa apakah ID produk telah diberikan melalui parameter URL
    if (isset($_GET['id'])) {
      $productId = $_GET['id'];

      // Query untuk mengambil informasi produk berdasarkan ID produk
      $query = "SELECT * FROM Product WHERE product_id = '$productId'";
      $result = mysqli_query($conn, $query);

      // Periksa apakah produk ditemukan
      if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $productName = $row['product_name'];
        $category = $row['category'];
        $description = $row['description'];
        $image = $row['image_url'];
        $price = $row['price'];

        // Tampilkan informasi produk
        ?>
        <div class="columns">
          <div class="column is-half">
            <figure class="image is-4by3">
              <img src="<?php echo './product_image/' . $image; ?>" alt="Product Image">
            </figure>
          </div>
          <div class="column is-half">
            <h2 class="title is-4"><?php echo $productName; ?></h2>
            <p><strong>Category:</strong> <?php echo $category; ?></p>
            <p><strong>Description:</strong> <?php echo $description; ?></p>
            <p><strong>Price:</strong> Rp <?php echo number_format($price, 0, ',', '.'); ?></p>
            <br>
            <div id="add-to-cart-message"></div>
            <div class="field has-addons">
              <?php
               if(isset($_SESSION['user'])){
              ?>
              <div class="control">
                <input class="input" type="number" id="quantity" name="quantity" min="1" value="1" onchange="updateQuantity()">
              </div>
              <div class="control">
                <button type="button" class="button is-primary" onclick="addToCart(<?php echo $productId; ?>)">Buy Now</button>
              </div>
              <?php } else{
                  echo '<br>
                  <p>Login to make a purchase.</p>';
              } ?>
            </div>
          </div>
        </div>
      <?php
      } else {
        // Jika produk tidak ditemukan
        echo "<p>Product not found.</p>";
      }
    } else {
      // Jika ID produk tidak diberikan
      echo "<p>Invalid product ID.</p>";
    }
    ?>
  </div>
</section>

<?php include 'footer.php'; ?>
<script>
function updateQuantity() {
  var quantity = parseInt($('#quantity').val());

  // Lakukan validasi apakah quantity bernilai numerik dan lebih besar dari 0
  if ($.isNumeric(quantity) && quantity >= 1) {
    // Tidak ada aksi khusus yang perlu dilakukan pada perubahan quantity
  } else {
    // Jika quantity tidak valid, set nilai quantity kembali ke 1
    quantity = 1;
  }

  $('#quantity').val(quantity);
}

function addToCart(productId) {
  var quantity = parseInt($('#quantity').val());
  
  $.ajax({
    url: 'add-to-cart.php',
    type: 'POST',
    data: { productId: productId, quantity: quantity },
    success: function(response) {
      $('#add-to-cart-message').html(response);
      alert(response);
      window.location.href = 'checkout.php';
    },
    error: function(xhr, status, error) {
      console.log('Error: ' + error);
    }
  });
}
</script>

