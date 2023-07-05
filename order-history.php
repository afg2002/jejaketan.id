<?php
include 'header.php';

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user'])) {
  // Jika pengguna belum login, arahkan ke halaman login
  echo "<script>location.href='index.php'</script>";
  exit();
}

// Mendapatkan ID pengguna dari sesi
$userId = $_SESSION['user']['user_id'];

// Mendefinisikan jumlah data per halaman
$perPage = 5;

// Mendapatkan halaman yang diminta dari parameter URL
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Menghitung offset (mulai) data berdasarkan halaman yang diminta
$offset = ($page - 1) * $perPage;

// Query untuk mendapatkan data pesanan pengguna (Aktif) dengan pagination
$activeQuery = "SELECT o.*, p.payment_method, s.full_name, s.email, s.address, s.phone_number
                FROM Orders o
                INNER JOIN PaymentMethod p ON o.order_id = p.order_id
                INNER JOIN ShippingDetails s ON o.order_id = s.order_id
                WHERE o.user_id = $userId
                AND o.status != 'Selesai'
                ORDER BY o.order_id DESC
                LIMIT $offset, $perPage";
$activeResult = mysqli_query($conn, $activeQuery);

// Menghitung total data pesanan pengguna (Aktif)
$totalActive = mysqli_num_rows(mysqli_query($conn, "SELECT o.order_id
                                                     FROM Orders o
                                                     WHERE o.user_id = $userId
                                                     AND o.status != 'Selesai'"));

// Menghitung total halaman berdasarkan total data dan jumlah data per halaman
$totalPagesActive = ceil($totalActive / $perPage);

// Query untuk mendapatkan data pesanan pengguna (Selesai) dengan pagination
$completedQuery = "SELECT o.*, p.payment_method, s.full_name, s.email, s.address, s.phone_number
                   FROM Orders o
                   INNER JOIN PaymentMethod p ON o.order_id = p.order_id
                   INNER JOIN ShippingDetails s ON o.order_id = s.order_id
                   WHERE o.user_id = $userId
                   AND (o.is_paid = 'Y' AND o.status = 'Selesai')
                   ORDER BY o.order_id DESC
                   LIMIT $offset, $perPage";
$completedResult = mysqli_query($conn, $completedQuery);

// Menghitung total data pesanan pengguna (Selesai)
$totalCompleted = mysqli_num_rows(mysqli_query($conn, "SELECT o.order_id
                                                        FROM Orders o
                                                        WHERE o.user_id = $userId
                                                        AND (o.is_paid = 'Y' AND o.status = 'Selesai')"));

// Menghitung total halaman berdasarkan total data dan jumlah data per halaman
$totalPagesCompleted = ceil($totalCompleted / $perPage);
?>

<section class="section">
  <div class="container">
    <h1 class="title">Order History</h1>

    <h2 class="subtitle">Orderan Aktif</h2>

    <table class="table is-striped is-fullwidth">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Order Date</th>
          <th>Payment Method</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Address</th>
          <th>Phone Number</th>
          <th>Order Items</th>
          <th>Status Pembayaran</th>
          <th>Status Pengiriman</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($activeResult)) {
          $orderId = $row['order_id'];
          echo "<tr>";
          echo "<td>" . $orderId . "</td>";
          echo "<td>" . $row['order_date'] . "</td>";
          echo "<td>" . $row['payment_method'] . "</td>";
          echo "<td>" . $row['full_name'] . "</td>";
          echo "<td>" . $row['email'] . "</td>";
          echo "<td>" . $row['address'] . "</td>";
          echo "<td>" . $row['phone_number'] . "</td>";
          echo "<td>";

          // Query untuk mendapatkan item pesanan
          $itemQuery = "SELECT oi.*, p.product_name, p.price
                        FROM OrderItem oi
                        INNER JOIN Product p ON oi.product_id = p.product_id
                        WHERE oi.order_id = $orderId";
          $itemResult = mysqli_query($conn, $itemQuery);

          echo "<table class='table is-fullwidth'>";
          echo "<thead>";
          echo "<tr>";
          echo "<th>Product Name</th>";
          echo "<th>Quantity</th>";
          echo "<th>Price</th>";
          echo "<th>Subtotal</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          while ($item = mysqli_fetch_assoc($itemResult)) {
            echo "<tr>";
            echo "<td>" . $item['product_name'] . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>Rp. " . number_format($item['price']) . "</td>";
            echo "<td>Rp. " . number_format($item['subtotal']) . "</td>";
            echo "</tr>";
          }

          echo "</tbody>";
          echo "</table>";

          echo "</td>";
          echo "<td>" . ($row['is_paid'] === 'Y' ? 'Sudah Dibayar' : 'Belum Dibayar') . "</td>";
          echo "<td>" . $row['status'] . "</td>";
          echo "<td>";

          if ($row['is_paid'] === 'N') {
            echo "<a class='button is-primary' href='payment.php?order_id=" . $orderId . "'>Bayar</a>";
          }

          echo "</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>

    <?php if ($totalActive > $perPage) : ?>
      <nav class="pagination" role="navigation" aria-label="pagination">
        <ul class="pagination-list">
          <?php if ($page > 1) : ?>
            <li>
              <a class="pagination-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
            </li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $totalPagesActive; $i++) : ?>
            <li>
              <a class="pagination-link <?php echo $i == $page ? 'is-current' : ''; ?>" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($page < $totalPagesActive) : ?>
            <li>
              <a class="pagination-link" href="?page=<?php echo $page + 1; ?>">Next</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>

    <h2 class="subtitle">Orderan Selesai</h2>

    <table class="table is-striped is-fullwidth">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Order Date</th>
          <th>Payment Method</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Address</th>
          <th>Phone Number</th>
          <th>Order Items</th>
          <th>Status Pembayaran</th>
          <th>Status Pengiriman</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($completedResult)) {
          $orderId = $row['order_id'];
          echo "<tr>";
          echo "<td>" . $orderId . "</td>";
          echo "<td>" . $row['order_date'] . "</td>";
          echo "<td>" . $row['payment_method'] . "</td>";
          echo "<td>" . $row['full_name'] . "</td>";
          echo "<td>" . $row['email'] . "</td>";
          echo "<td>" . $row['address'] . "</td>";
          echo "<td>" . $row['phone_number'] . "</td>";
          echo "<td>";

          // Query untuk mendapatkan item pesanan
          $itemQuery = "SELECT oi.*, p.product_name, p.price
                        FROM OrderItem oi
                        INNER JOIN Product p ON oi.product_id = p.product_id
                        WHERE oi.order_id = $orderId";
          $itemResult = mysqli_query($conn, $itemQuery);

          echo "<table class='table is-fullwidth'>";
          echo "<thead>";
          echo "<tr>";
          echo "<th>Product Name</th>";
          echo "<th>Quantity</th>";
          echo "<th>Price</th>";
          echo "<th>Subtotal</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          while ($item = mysqli_fetch_assoc($itemResult)) {
            echo "<tr>";
            echo "<td>" . $item['product_name'] . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>Rp. " . number_format($item['price']) . "</td>";
            echo "<td>Rp. " . number_format($item['subtotal']) . "</td>";
            echo "</tr>";
          }

          echo "</tbody>";
          echo "</table>";

          echo "</td>";
          echo "<td>" . ($row['is_paid'] === 'Y' ? 'Sudah Dibayar' : 'Belum Dibayar') . "</td>";
          echo "<td>" . $row['status'] . "</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>

    <?php if ($totalCompleted > $perPage) : ?>
      <nav class="pagination" role="navigation" aria-label="pagination">
        <ul class="pagination-list">
          <?php if ($page > 1) : ?>
            <li>
              <a class="pagination-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
            </li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $totalPagesCompleted; $i++) : ?>
            <li>
              <a class="pagination-link <?php echo $i == $page ? 'is-current' : ''; ?>" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($page < $totalPagesCompleted) : ?>
            <li>
              <a class="pagination-link" href="?page=<?php echo $page + 1; ?>">Next</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</section>
