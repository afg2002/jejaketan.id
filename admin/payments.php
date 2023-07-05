<?php
include '../header.php';

// Periksa apakah pengguna telah login dan memiliki hak akses sebagai admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Jika pengguna belum login atau bukan admin, arahkan ke halaman lain
    header("Location: index.php");
    exit();
}

// Aksi hapus data pembayaran
if (isset($_GET['delete_payment'])) {
    $orderId = $_GET['delete_payment'];

    // Lakukan proses penghapusan data pembayaran sesuai order_id
    // Misalnya, jika tabel pembayaran memiliki kolom order_id dengan nama 'order_id'
    $deletePaymentQuery = "DELETE FROM Payment WHERE order_id = '$orderId'";
    mysqli_query($conn, $deletePaymentQuery);

    // Redirect atau tampilkan pesan sukses penghapusan
    header("Location: payments.php");
    exit();
}

// Aksi update status pembayaran dan status pesanan
if (isset($_POST['update_payment'])) {
    $orderId = $_POST['order_id'];
    $statusPembayaran = $_POST['status_pembayaran'];
    $statusPesanan = $_POST['status_pesanan'];

    // Lakukan proses update status pembayaran dan status pesanan sesuai order_id
    // Misalnya, jika tabel order memiliki kolom order_id, status_pembayaran, dan status_pesanan
    $updatePaymentQuery = "UPDATE Orders SET status = '$statusPesanan', is_paid = '$statusPembayaran' WHERE order_id = '$orderId'";
    mysqli_query($conn, $updatePaymentQuery);

    // Redirect atau tampilkan pesan sukses update
    header("Location: payments.php");
    exit();
}

// Query untuk mendapatkan data pembayaran dengan inner join
$query = "SELECT o.order_id, pm.image_url,o.order_date, o.is_paid, o.status, p.product_id, p.product_name, od.quantity, od.subtotal, pmth.payment_method, s.full_name, s.email, s.address, s.phone_number
            FROM Orders o
            INNER JOIN OrderItem od ON o.order_id = od.order_id
            INNER JOIN Product p ON od.product_id = p.product_id
            INNER JOIN Payment pm ON o.order_id = pm.order_id
            INNER JOIN PaymentMethod pmth ON pm.order_id = pmth.order_id
            INNER JOIN ShippingDetails s ON o.order_id = s.order_id
            ORDER BY o.order_id";

$result = mysqli_query($conn, $query);
?>

<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Data Pembayaran</h1>

            <?php
            $currentOrderId = null;
            $grandTotal = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['order_id'] !== $currentOrderId) {
                    // Baris pertama untuk order ID baru
                    if ($currentOrderId !== null) {
                        // Tampilkan total keseluruhan sebelum tombol "Update" dan "Delete"
                        echo "<tr>";
                        echo "<td colspan='3'></td>";
                        echo "<td><strong>Total: </strong></td>";
                        echo "<td> Rp" . number_format($grandTotal,2,',','.') . "</td>";
                        echo "<td colspan='5'></td>";
                        echo "</tr>";

                        // Tutup subtable sebelum beralih ke order ID baru
                        echo "</tbody>";
                        echo "</table>";
                        echo "<div class='field is-grouped'>";
                        echo "<div class='control'>";
                        echo "<button type='submit' name='update_payment' class='button is-primary'>Update</button>";
                        echo "</div>";
                        echo "<div class='control'>";
                        echo "<a href='?delete_payment=" . $currentOrderId . "' class='button is-danger'>Delete</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                    }

                    // Mulai subtable untuk order ID baru
                    echo "<div class='box'>";
                    echo "<h3 class='subtitle'>Order ID: " . $row['order_id'] . "</h3>";
                    echo "<p>Order Date: " . $row['order_date'] . "</p>";
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='order_id' value='" . $row['order_id'] . "'>";
                    echo "<div class='field'>
                    <label class='label'>Payment Proof</label>
                    <img width=80 src ='".BASE_URL.'payment_proof/'.$row['image_url']."'/>
                    </div>";
                    echo "<div class='field'>";
                    echo "<label class='label'>Status Pembayaran</label>";
                    echo "<div class='control'>";
                    echo "<div class='select'>";
                    echo "<select name='status_pembayaran'>";
                    echo "<option value='Y' " . ($row['is_paid'] === 'Y' ? 'selected' : '') . ">Sudah Dibayar</option>";
                    echo "<option value='N' " . ($row['is_paid'] === 'N' ? 'selected' : '') . ">Belum Dibayar</option>";
                    echo "</select>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='field'>";
                    echo "<label class='label'>Status Pesanan</label>";
                    echo "<div class='control'>";
                    echo "<div class='select'>";
                    echo "<select name='status_pesanan'>";
                    echo "<option value='Diproses' " . ($row['status'] === 'Diproses' ? 'selected' : '') . ">Diproses</option>";
                    echo "<option value='Dikirim' " . ($row['status'] === 'Dikirim' ? 'selected' : '') . ">Dikirim</option>";
                    echo "<option value='Selesai' " . ($row['status'] === 'Selesai' ? 'selected' : '') . ">Selesai</option>";
                    echo "</select>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='table-container'>";
                    echo "<table class='table is-fullwidth'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Product ID</th>";
                    echo "<th>Product Name</th>";
                    echo "<th>Quantity</th>";
                    echo "<th>Subtotal</th>";
                    echo "<th>Payment Method</th>";
                    echo "<th>Full Name</th>";
                    echo "<th>Email</th>";
                    echo "<th>Address</th>";
                    echo "<th>Phone Number</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    $currentOrderId = $row['order_id'];
                    $grandTotal = 0;
                }

                // Baris produk dalam subtable
                echo "<tr>";
                echo "<td>" . $row['product_id'] . "</td>";
                echo "<td>" . $row['product_name'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $row['subtotal'] . "</td>";
                echo "<td>" . $row['payment_method'] . "</td>";
                echo "<td>" . $row['full_name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['address'] . "</td>";
                echo "<td>" . $row['phone_number'] . "</td>";
                echo "</tr>";

                // Tambahkan subtotal produk ke total keseluruhan
                $grandTotal += $row['subtotal'];
            }

            // Tutup subtable terakhir setelah loop
            if ($currentOrderId !== null) {
                echo "</tbody>";
                echo "<tfoot>";
                echo "<tr>";
                echo "<td colspan='3'></td>";
                echo "<td>Total</td>";
                // Rp " . number_format($angka,2,',','.');
                echo "<td> Rp" . number_format($grandTotal,2,',','.') . "</td>";
                echo "<td colspan='5'></td>";
                echo "</tr>";
                echo "</tfoot>";
                echo "</table>";
                echo "<div class='field is-grouped'>";
                echo "<div class='control'>";
                echo "<button type='submit' name='update_payment' class='button is-primary'>Update</button>";
                echo "</div>";
                echo "<div class='control'>";
                echo "<a href='?delete_payment=" . $currentOrderId . "' class='button is-danger'>Delete</a>";
                echo "</div>";
                echo "</div>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
            ?>

        </div>
    </section>
</body>

<?php
include '../footer.php';
?>
