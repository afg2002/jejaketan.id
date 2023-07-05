<?php include 'header.php'; ?>
<style>
  .card {
    height: 100%;
  }
  .card-footer{
    padding:10px;
  }
  .card-footer.product-footer .card-footer-item + .card-footer-item {
    margin-left: 20px;
  }
</style>
<section class="section">
  <div class="container">
    <h1 class="title">Products</h1>

    <div class="columns is-centered">
      <div class="column is-half">
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

        <form action="" method="GET">
          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Category</label>
            </div>
            <div class="field-body">
              <div class="field">
                <div class="control">
                  <div class="select is-fullwidth">
                    <select name="category" onchange="this.form.submit()">
                      <option value="" <?php echo empty($_GET['category']) ? 'selected' : ''; ?>>All</option>
                      <option value="Kids' Jacket" <?php echo (isset($_GET['category']) && $_GET['category'] === "Kids' Jacket") ? 'selected' : ''; ?>>Kids' Jacket</option>
                      <option value="Men's Jacket" <?php echo (isset($_GET['category']) && $_GET['category'] === "Men's Jacket") ? 'selected' : ''; ?>>Men's Jacket</option>
                      <option value="Women's Jacket" <?php echo (isset($_GET['category']) && $_GET['category'] === "Women's Jacket") ? 'selected' : ''; ?>>Women's Jacket</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="columns is-multiline">
      <?php
      // Query untuk mengambil data produk dari tabel "Product" berdasarkan kata kunci pencarian dan filter kategori
      $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
      $category = isset($_GET['category']) ? $_GET['category'] : '';
      $query = "SELECT * FROM Product WHERE 1=1";

      if (!empty($searchKeyword)) {
        $query .= " AND product_name LIKE '%$searchKeyword%'";
      }

      if (!empty($category)) {
        $query .= " AND category = \"$category\"";
      }

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


<?php include 'footer.php'; ?>
<script>

$(document).ready(function() {
    // Tambahkan event listener untuk tombol "Add to Cart"
    $('.add-to-cart').click(function(e) {
      e.preventDefault();
      var productId = $(this).data('product-id');
  
      // Kirim permintaan Ajax ke file add-to-cart.php
      $.ajax({
        url: 'add-to-cart.php',
        method: 'POST',
        data: {
          productId: productId
        },
        success: function(response) {
          // Tampilkan pesan sukses atau error
          alert(response);
        },
        error:function(response){
            console.log(response);
        }
      });
    });
  });
</script>

