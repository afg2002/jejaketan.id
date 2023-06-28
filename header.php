<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jejaketan.id - Toko Jaket Online Kekinian</title>
    <link rel="stylesheet" href="css/bulma.min.css">
  </head>
  <body>
  <!-- Navbar -->
<nav class="navbar is-primary" role="navigation" aria-label="main navigation">
  <!-- Navbar Brand -->
  <div class="navbar-brand">
    <a class="navbar-item" href="#">
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
      <a class="navbar-item" href="./">Home</a>
      <a class="navbar-item" href="products.php">Products</a>
      <a class="navbar-item" href="about.php">About</a>
      <a class="navbar-item" href="contact.php">Contact</a>
    </div>

    <!-- Navbar End -->
    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <a class="button is-primary" href="#">
            <strong id="signup-button">Sign up</strong>
          </a>
          <a class="button is-light" href="#" id="login-button">
            Log in
          </a>
        </div>
      </div>
    </div>
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
    <section class="modal-card-body">
      <!-- Login Form -->
      <form>
        <div class="field">
          <label class="label">Email</label>
          <div class="control">
            <input class="input" type="email" placeholder="Enter your email">
          </div>
        </div>
        <div class="field">
          <label class="label">Password</label>
          <div class="control">
            <input class="input" type="password" placeholder="Enter your password">
          </div>
        </div>
      </form>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-primary">Log in</button>
      <button class="button" id="cancel-button">Cancel</button>
    </footer>
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
      <form>
        <div class="field">
          <label class="label">Name</label>
          <div class="control">
            <input class="input" type="text" placeholder="Enter your name">
          </div>
        </div>
        <div class="field">
          <label class="label">Email</label>
          <div class="control">
            <input class="input" type="email" placeholder="Enter your email">
          </div>
        </div>
        <div class="field">
          <label class="label">Password</label>
          <div class="control">
            <input class="input" type="password" placeholder="Enter your password">
          </div>
        </div>
      </form>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-primary">Sign Up</button>
      <button class="button" id="cancel-signup-button">Cancel</button>
    </footer>
  </div>
</div>




