<?php
include '../header.php';
// Periksa apakah pengguna sudah login dan merupakan admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Jika bukan admin, redirect ke halaman lain atau tampilkan pesan error
    header('Location: index.php');
    exit();
}

// Periksa apakah parameter id pengguna ada
if (!isset($_GET['id'])) {
    // Jika tidak ada parameter id, redirect ke halaman lain atau tampilkan pesan error
    echo '<script>location.href = "users.php"</script>';
    exit();
}

// Ambil id pengguna dari parameter
$user_id = $_GET['id'];

// Periksa apakah pengguna dengan id tersebut ada di database
$query = "SELECT * FROM User WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    // Jika pengguna dengan id tersebut tidak ditemukan, redirect ke halaman lain atau tampilkan pesan error
    echo '<script>location.href = "users.php"</script>';
    exit();
}

// Jika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];

    // Perbarui data pengguna di database
    $query = "UPDATE User SET email = '$email', full_name = '$full_name', address = '$address', phone_number = '$phone_number'";
    
    // Periksa apakah password diisi atau tidak
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query .= ", password = '$hashed_password'";
    }
    
    $query .= " WHERE user_id = '$user_id'";
    
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Jika pembaruan berhasil, perbarui juga data pengguna dalam sesi
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['full_name'] = $full_name;
        $_SESSION['user']['address'] = $address;
        $_SESSION['user']['phone_number'] = $phone_number;

        // Redirect ke halaman users.php
        echo '<script>location.href = "users.php"</script>';
        exit();
    } else {
        // Jika pembaruan gagal, tampilkan pesan error
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>

<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Admin - Edit Pengguna</h1>

            <!-- Form Edit Pengguna -->
            <form method="POST">
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input class="input" type="email" name="email" value="<?php echo $row['email']; ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Nama Lengkap</label>
                    <div class="control">
                        <input class="input" type="text" name="full_name" value="<?php echo $row['full_name']; ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Alamat</label>
                    <div class="control">
                        <textarea class="textarea" name="address" required><?php echo $row['address']; ?></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Nomor Telepon</label>
                    <div class="control">
                        <input class="input" type="text" name="phone_number" value="<?php echo $row['phone_number']; ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                    <div class="control">
                        <input class="input" type="password" name="password">
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-link">Simpan</button>
                        <a class="button is-danger" href="users.php">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <?php include '../footer.php'; ?>
</body>
