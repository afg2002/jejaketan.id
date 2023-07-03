<?php

include 'config/db.php';
session_start();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jejaketan.id - Toko Jaket Online Kekinian</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/bulma.min.css">
    
  </head>
  <body>
  <!-- Navbar -->
<nav class="navbar is-primary" role="navigation" aria-label="main navigation">
  <!-- Navbar Brand -->
  <div class="navbar-brand">
    <a class="navbar-item" href="<?php echo BASE_URL ?>">
      <h1 class="title is-4 has-text-white">Jejaketan.id</h1>
    </a>
    <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <!-- Navbar Menu -->
  <div id="navbarBasicExample" class="navbar-menu">
    <!-- Navbar Start -->
    <div class="navbar-start">
        <a class="navbar-item" href="<?php echo BASE_URL ?>">Home</a>
        <?php 
        // Periksa apakah pengguna adalah admin
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            // Tampilkan menu data pengguna dan data produk
            echo '
            <a class="navbar-item" href="'.BASE_URL.'products.php">Products</a>
            <a class="navbar-item" href="'.BASE_URL.'admin/users.php">[A] Data User</a>
            <a class="navbar-item" href="'.BASE_URL.'admin/products.php">[A] Data Produk</a>
            ';
        } else {
            // Tampilkan menu biasa
            echo '
            <a class="navbar-item" href="./products.php">Products</a>
            <a class="navbar-item" href="./about.php">About</a>
            <a class="navbar-item" href="./contact.php">Contact</a>
            ';
        }
        ?>
    </div>


    <!-- Navbar End -->
    <?php 
    // Cek apakah pengguna sudah login
    if (isset($_SESSION['user'])) {
        // Pengguna sudah login, dapatkan data pengguna
        $user = $_SESSION['user'];
    
        // Tampilkan tombol dropdown nama pengguna dan menu logout
        echo '
        <div class="navbar-end">
          <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">' . $user['full_name'] . '</a>
            <div class="navbar-dropdown">
              <a class="navbar-item" href="'.BASE_URL.'/profile.php">Profile</a>
              <a class="navbar-item" href="'.BASE_URL.'/config/logout.php">Logout</a>
            </div>
          </div>
        </div>';
    } else {
        // Pengguna belum login, tampilkan tombol sign up dan log in
        echo '
        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <a class="button is-primary" href="#" id="signup-button">
                <strong>Sign up</strong>
              </a>
              <a class="button is-light" href="#" id="login-button">
                Log in
              </a>
            </div>
          </div>
        </div>';
    }
    ?>
  </div>
</nav>

<!-- Modal Login -->
<div class="modal" id="modal-login">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Log in</p>
      <button class="delete" aria-label="close"></button>
    </header>
    <!-- Form Login -->
    <section class="modal-card-body">
      <form method="POST" action="config/login.php">
        <div class="field">
          <label class="label">Email</label>
          <div class="control">
            <input class="input" type="email" placeholder="Enter your email" name="email">
          </div>
        </div>
        <div class="field">
          <label class="label">Password</label>
          <div class="control">
            <input class="input" type="password" placeholder="Enter your password" name="password">
          </div>
        </div>
        <?php if (isset($error)) { ?>
            <p class="help is-danger"><?php echo $error; ?></p>
        <?php } ?>
        <div class="field">
          <div class="control">
            <button class="button is-primary" type="submit">Log in</button>
          </div>
        </div>
      </form>
    </section>
  </div>
</div>

<!-- Modal Sign Up -->
<div class="modal" id="modal-signup">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Sign Up</p>
      <button class="delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
  <!-- Sign Up Form -->
    <form action="config/register-user.php" method="POST">
        <div class="field">
          <label class="label">Full Name</label>
          <div class="control">
            <input class="input" type="text" placeholder="Enter your name" name="fullname">
          </div>
        </div>
        <div class="field">
          <label class="label">Email</label>
          <div class="control">
            <input class="input" type="email" placeholder="Enter your email" name="email">
          </div>
        </div>
        <div class="field">
          <label class="label">Password</label>
          <div class="control">
            <input class="input" type="password" placeholder="Enter your password" name="password">
          </div>
        </div>
        <div class="field">
          <label class="label">Address</label>
          <div class="control">
            <input class="input" type="text" placeholder="Enter your address" name="address">
          </div>
        </div>
        <div class="field">
          <label class="label">Phone Number</label>
          <div class="control">
            <input class="input" type="text" placeholder="Enter your phone number" name="phone_number">
          </div>
        </div>
      
    </section>

    <footer class="modal-card-foot">
      <button class="button is-primary" type="submit">Sign Up</button>
      <button class="button" id="cancel-signup-button">Cancel</button>
    </footer>
    </form>
  </div>
</div>



