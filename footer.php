
<footer class="footer">
      <div class="content has-text-centered">
        <p>
          &copy; 2023 Jejaketan.id. All rights reserved.
        </p>
      </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
      // Navbar Burger Toggle
      $(document).ready(function() {
        // Toggle Modal
        $('.navbar-burger').click(function() {
          var target = $(this).data('target');
          $(this).toggleClass('is-active');
          $('#' + target).toggleClass('is-active');
        });

        // Close Modal
        $('.modal .delete').click(function() {
          $(this).closest('.modal').removeClass('is-active');
        });

        // Show Modal Login
        $('#login-button').click(function() {
          $('#modal-login').addClass('is-active');
        });
      });

       // Show Modal Sign Up
        $('#signup-button').click(function() {
        $('#modal-signup').addClass('is-active');
        });

        // Close Modal Sign Up
        $('#cancel-signup-button').click(function() {
        $('#modal-signup').removeClass('is-active');
        });

        
    </script>
    <?php ob_end_flush(); ?>
  </body>
</html>