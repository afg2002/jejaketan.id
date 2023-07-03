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

// Periksa apakah pengguna yang akan dihapus bukan pengguna yang sedang login
if ($_SESSION['user']['user_id'] === $user_id) {
    // Jika pengguna yang akan dihapus adalah pengguna yang sedang login, redirect ke halaman lain atau tampilkan pesan error
    echo '<script>location.href = "users.php"</script>';
    exit();
}

// Jika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus pengguna dari database
    $query = "DELETE FROM User WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Jika penghapusan berhasil, redirect ke halaman users.php
        echo '<script>location.href = "users.php"</script>';
        exit();
    } else {
        // Jika penghapusan gagal, tampilkan pesan error
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>

<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Admin - Hapus Pengguna</h1>

            <div class="notification is-danger">
                <p>Anda yakin ingin menghapus pengguna ini?</p>
                <p>Email: <?php echo $row['email']; ?></p>
                <p>Nama: <?php echo $row['full_name']; ?></p>
            </div>

            <!-- Form Konfirmasi Hapus Pengguna -->
            <form method="POST">
                <div class="field is-grouped">
                    <div class="control">
                        <button class="button is-danger" type="submit">Hapus Pengguna</button>
                    </div>
                    <div class="control">
                        <a class="button is-link" href="users.php">Batalkan</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <?php include '../footer.php'  ?>
