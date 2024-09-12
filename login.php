<?php
session_start();
if (isset($_SESSION['error'])) {
  echo "<script>alert('" . $_SESSION['error'] . "');</script>";
  unset($_SESSION['error']);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login IC</title>
  <link rel="stylesheet" href="./login.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>

<body>
  <section>
    <div class="noise-bg"></div>

    <!-- Sign In Form -->
    <div class="signin">
      <div class="content">
        <h2>Sign In</h2>
        <form method="POST" action="signin.php">
          <div class="inputBox">
            <input type="text" name="username" value="<?php 
              echo (isset($_COOKIE['remember_me']) && $_COOKIE['remember_me'] === $_SESSION['remember_me_token']) ? $_SESSION['username'] : ''; 
              ?>"required> <i>Username</i>
          </div>
          <div class="inputBox">
            <input type="password" name="password" required> <i>Password</i>
          </div>
           <div class="inputBox remember-me">
            <input type="checkbox" name="remember_me" id="remember_me" <?php
              echo (isset($_COOKIE['remember_me']) && $_COOKIE['remember_me'] === $_SESSION['remember_me_token']) ? 'checked' : '';?>>
            <label for="remember_me">Remember Me</label>
          </div>

          <div class="links">
            <a href="#">Don't have an account?</a>
            <a href="#" id="toggle-signup">Sign Up</a>
          </div>
          <div class="inputBox">
            <input type="submit" name="submit" value="Sign In">
          </div>
        </form>
      </div>
    </div>

    <!-- Sign Up Form -->
    <div class="signup">
      <div class="content">
        <h2>Sign Up</h2>
        <form method="POST" action="signup.php" onsubmit="return validatePassword();">
          <div class="inputBox">
            <input type="text" name="firstname" required> <i>First Name</i>
          </div>
          <div class="inputBox">
            <input type="text" name="lastname" required> <i>Last Name</i>
          </div>
          <div class="inputBox">
            <input type="text" name="username" required> <i>Username</i>
          </div>
          <div class="inputBox">
            <input type="password" id="password" name="password" required> <i>Password</i>
          </div>
          <div class="inputBox">
            <input type="password" id="confirmpassword" name="confirmpassword" required> <i>Confirm Password</i>
          </div>
          <div class="links">
            <a href="#">Already have an account?</a>
            <a href="#" id="toggle-signin">Sign In</a>
          </div>
          <div class="inputBox">
            <input type="submit" name="submit" value="Sign Up">
          </div>
        </form>
      </div>
    </div>
  </section>

  <script>
    $(document).ready(function () {
      const toggleSignup = $('#toggle-signup');
      const toggleSignin = $('#toggle-signin');
      const signinForm = $('.signin');
      const signupForm = $('.signup');

      $(toggleSignup).click(function () {
        signinForm.hide();
        signupForm.css('display', 'flex');
      });

      $(toggleSignin).click(function () {
        signupForm.hide();
        signinForm.css('display', 'flex');
      });

      function validatePassword() {
        var password = $("#password").val();
        var confirmPassword = $("#confirmpassword").val();

        if (password !== confirmPassword) {
          alert("Passwords do not match!");
          return false;
        } else {
          return true;
        }
      }

      $(signupForm).hide();
    });
  </script>
</body>

</html>