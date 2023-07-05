<?php include 'header.php' ?>

<style>
    .card{
        height: 100%;
    }
</style>

<section class="hero is-primary is-bold">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Welcome to Jejaketan.id!
            </h1>
            <h2 class="subtitle">
                Your one-stop shop for trendy and fashionable jackets.
            </h2>
            <a class="button is-primary is-large" href="products.php">Shop Now</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h1 class="title">Featured Products</h1>
        <div class="columns is-multiline">
      <?php
      $query = "SELECT * FROM Product WHERE is_featured = 'Y'";

      $result = mysqli_query($conn, $query);

      // Periksa apakah ada data produk yang ditemukan
      if (mysqli_num_rows($result) > 0) {
        // Loop melalui setiap baris data produk
        while ($row = mysqli_fetch_assoc($result)) {
          $productName = $row['product_name'];
          $productId = $row['product_id'];
          $category = $row['category'];
          $description = $row['description'];
          $image = $row['image_url'];

          // Pemotongan deskripsi produk jika terlalu panjang
          $maxDescriptionLength = 120; // Jumlah maksimum karakter deskripsi
          if (strlen($description) > $maxDescriptionLength) {
            $shortDescription = substr($description, 0, $maxDescriptionLength) . '...';
          } else {
            $shortDescription = $description;
          }
          ?>
          <div class="column is-one-third">
            <div class="card">
              <div class="card-image">
                <figure class="image is-4by3">
                  <a href="details-product.php?id=<?php echo $productId; ?>">
                    <img src="<?php echo './product_image/' . $image; ?>" alt="Product Image">
                  </a>
                </figure>
              </div>
              <div class="card-content" style="height:200px;">
                <div class="content">
                  <h3 class="title is-5">
                    <a href="details-product.php?id=<?php echo $productId; ?>"><?php echo $productName; ?></a>
                  </h3>
                  <p class="subtitle is-6">
                    <a href="products.php?category=<?php echo urlencode($category); ?>"><?php echo $category; ?></a>
                  </p>
                  <p><?php echo $shortDescription; ?></p>
                </div>
              </div>
              <footer class="card-footer product-footer">
                <?php
                if (isset($_SESSION['user'])) {
                  // Jika pengguna sudah login, tampilkan tombol "Buy Now" dan "Add to Cart"
                  ?>
                  <a class="card-footer-item button is-primary" href="details-product.php?id=<?php echo $productId; ?>">
                    Buy Now
                  </a>
                  <a class="card-footer-item button is-primary add-to-cart" data-product-id="<?php echo $productId; ?>" href="#">
                    Add to Cart
                  </a>
                  <?php
                } else {
                  // Jika pengguna belum login, jangan tampilkan tombol "Buy Now" dan "Add to Cart"
                  ?>
                  <p class="card-footer-item">Please login to make a purchase.</p>
                  <?php
                }
                ?>
              </footer>
            </div>
          </div>
        <?php
        }
      } else {
        // Jika tidak ada data produk yang ditemukan
        echo "<p>Tidak ada produk yang tersedia.</p>";
      }
      ?>
    </div>
    </div>
</section>

<?php include 'footer.php' ?>
