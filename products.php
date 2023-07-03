<?php include 'header.php'; ?>
<style>
  .card {
    height: 100%;
  }
</style>

<section class="section">
  <div class="container">
    <h1 class="title">Products</h1>

    <!-- Tambahkan elemen pencarian -->
    <form action="" method="GET" class="mb-4">
      <div class="field has-addons">
        <div class="control is-expanded">
          <input class="input" type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        </div>
        <div class="control">
          <button type="submit" class="button is-primary">Search</button>
        </div>
      </div>
    </form>

    <div class="columns is-multiline">
      <?php
      // Query untuk mengambil data produk dari tabel "Product" berdasarkan kata kunci pencarian
      $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
      $query = "SELECT * FROM Product WHERE product_name LIKE '%$searchKeyword%'";
      $result = mysqli_query($conn, $query);

      // Periksa apakah ada data produk yang ditemukan
      if (mysqli_num_rows($result) > 0) {
        // Loop melalui setiap baris data produk
        while ($row = mysqli_fetch_assoc($result)) {
          $productName = $row['product_name'];
          $category = $row['category'];
          $description = $row['description'];
          $image = $row['image_url'];
      ?>
          <div class="column is-one-third">
            <div class="card">
              <div class="card-image">
                <figure class="image is-4by3">
                  <img src="<?php echo './product_image/' . $image; ?>" alt="Product Image">
                </figure>
              </div>
              <div class="card-content">
                <div class="content">
                  <h3 class="title is-5"><?php echo $productName; ?></h3>
                  <p class="subtitle is-6">Category: <?php echo $category; ?></p>
                  <p><?php echo $description; ?></p>
                  <a class="button is-primary" href="#">View Details</a>
                </div>
              </div>
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

<?php include 'footer.php'; ?>
