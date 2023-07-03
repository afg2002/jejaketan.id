<?php include 'header.php' ?>

<section class="hero is-primary is-bold">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Welcome to Jejaketan.id!
            </h1>
            <h2 class="subtitle">
                Your one-stop shop for trendy and fashionable jackets.
            </h2>
            <a class="button is-primary is-large" href="#">Shop Now</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h1 class="title">Featured Products</h1>
        <div class="columns is-multiline">
            <?php
            // Query untuk mengambil produk dengan is_featured = 'Y'
            $query = "SELECT * FROM Product WHERE is_featured = 'Y'";
            $result = mysqli_query($conn, $query);

            // Periksa apakah ada hasil query
            if (mysqli_num_rows($result) > 0) {
                // Loop melalui hasil query
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="column is-one-third">
                        <div class="card">
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <img src="product_image/<?php echo $row['image_url']; ?>" alt="Product Image">
                                </figure>
                            </div>
                            <div class="card-content">
                                <div class="content">
                                    <h3 class="title is-5"><?php echo $row['product_name']; ?></h3>
                                    <p><?php echo $row['description']; ?></p>
                                    <a class="button is-primary" href="#">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo "<p>No featured products available.</p>";
            }
            ?>
        </div>
    </div>
</section>

<?php include 'footer.php' ?>
