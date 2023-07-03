<?php
ob_start(); // Memulai output buffering
include 'header.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    // Pengguna belum login, redirect ke halaman login atau halaman lain yang Anda inginkan
    header('Location: login.php');
    exit();
}

// Dapatkan data pengguna dari sesi
$user = $_SESSION['user'];

// Inisialisasi variabel untuk menyimpan nilai form
$email = $user['email'];
$fullName = $user['full_name'];
$address = $user['address'];
$phoneNumber = $user['phone_number'];

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil nilai form yang diedit
    $email = $_POST['email'];
    $fullName = $_POST['full_name'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phone_number'];

    // TODO: Lakukan validasi data form

    // Perbarui data pengguna di database
    $query = "UPDATE User SET email = '$email', full_name = '$fullName', address = '$address', phone_number = '$phoneNumber' WHERE user_id = " . $user['user_id'];
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Update sukses, dapatkan data pengguna yang diperbarui dari database
        $query = "SELECT * FROM User WHERE user_id = " . $user['user_id'];
        $result = mysqli_query($conn, $query);
        $updatedUser = mysqli_fetch_assoc($result);

        // Perbarui data pengguna di sesi
        $_SESSION['user'] = $updatedUser;

        // Redirect atau tampilkan pesan sukses
        echo "<script>alert('Berhasil diubah')</script>";
        header('Location: profile.php');
        
        exit();
    } else {
        // Update gagal, tampilkan pesan error
        echo "<script>alert('Gagal memperbarui profil. Silakan coba lagi.');</script>";
    }
}
ob_end_flush(); // Mengakhiri output buffering
?>


<section class="section">
    <div class="container">
        <h1 class="title">Profile</h1>
        <div class="content">
            <form method="POST">
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input class="input" type="email" name="email" value="<?php echo $email; ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Full Name</label>
                    <div class="control">
                        <input class="input" type="text" name="full_name" value="<?php echo $fullName; ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Address</label>
                    <div class="control">
                        <input class="input" type="text" name="address" value="<?php echo $address; ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Phone Number</label>
                    <div class="control">
                        <input class="input" type="text" name="phone_number" value="<?php echo $phoneNumber; ?>" required>
                    </div>
                </div>
                <div class="field is-grouped">
                    <div class="control">
                        <button class="button is-primary" type="submit">Simpan</button>
                    </div>
                    <div class="control">
                        <button class="button is-link" type="reset">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
