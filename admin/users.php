<?php
include '../header.php';
// Periksa apakah pengguna sudah login dan merupakan admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Jika bukan admin, redirect ke halaman lain atau tampilkan pesan error
    header('Location: index.php');
    exit();
}

$query = "SELECT * FROM User";
$result = mysqli_query($conn, $query);

// Periksa apakah query berhasil dijalankan
if (!$result) {
    // Jika query gagal, tampilkan pesan error
    echo 'Error: ' . mysqli_error($conn);
    exit();
}
?>
<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Admin - Data Pengguna</h1>

            <!-- Tabel Data Pengguna -->
            <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>Nomor Telepon</th>
                        <th>Role</th>
                        <th>Dibuat Pada</th>
                        <th>Diperbarui Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Tampilkan data pengguna
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '
                        <tr>
                            <td>' . $row['user_id'] . '</td>
                            <td>' . $row['email'] . '</td>
                            <td>' . $row['full_name'] . '</td>
                            <td>' . $row['address'] . '</td>
                            <td>' . $row['phone_number'] . '</td>
                            <td>' . $row['role'] . '</td>
                            <td>' . $row['created_at'] . '</td>
                            <td>' . $row['updated_at'] . '</td>
                            <td>
                                <a class="button is-link is-small" href="update_user.php?id=' . $row['user_id'] . '">Update</a>
                                <a class="button is-danger is-small" href="delete_user.php?id=' . $row['user_id'] . '">Delete</a>
                            </td>
                        </tr>
                        ';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php include '../footer.php'  ?>
