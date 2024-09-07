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
            <input type="text" name="username" required> <i>Username</i>
          </div>
          <div class="inputBox">
            <input type="password" name="password" required> <i>Password</i>
          </div>
          <div class="links">
            <a href="#">Forgot Password?</a>
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
    const toggleSignup = document.getElementById('toggle-signup');
    const toggleSignin = document.getElementById('toggle-signin');
    const signinForm = document.querySelector('.signin');
    const signupForm = document.querySelector('.signup');

    toggleSignup.addEventListener('click', () => {
      signinForm.style.display = 'none';
      signupForm.style.display = 'flex';
    });

    toggleSignin.addEventListener('click', () => {
      signupForm.style.display = 'none';
      signinForm.style.display = 'flex';
    });

    function validatePassword() {
      var password = document.getElementById("password").value;
      var confirmPassword = document.getElementById("confirmpassword").value;

      if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return false;
      } else {
        return true;
      }
    }

    signupForm.style.display = 'none';
  </script>
</body>

</html>