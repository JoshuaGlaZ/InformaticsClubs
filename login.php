<?php
session_start();
if (isset($_SESSION['error'])) {
  echo "<script>alert('" . $_SESSION['error'] . "');</script>";
  unset($_SESSION['error']);
}
if (isset($_SESSION['username'])) {
  if ($_SESSION['username'] == 'member') {
    header("Location: index.php");
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login IC</title>
  <link rel="stylesheet" href="assets/login.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>

<body>
  <section>
    <div class="noise-bg"></div>

    <!-- Sign In Form -->
    <div class="signin">
      <div class="content">
        <h2>Sign In</h2>
        <form method="POST" action="./actions/member.php">
          <div class="inputBox">
            <input type="text" name="username" value="<?php
              echo (isset($_COOKIE['remember_me']) && isset($_SESSION['remember_me_token']) && $_COOKIE['remember_me'] === $_SESSION['remember_me_token']) ? $_SESSION['username'] : '';
              ?>" required> <i>Username</i>
          </div>
          <div class="inputBox">
            <input type="password" name="password" id="signinPassword" required> <i>Password</i>
            <svg id="showEyeSignin" class="toggle-password" xmlns="http://www.w3.org/2000/svg" width="25" viewBox="0 0 512 512"><title>eye-glyph</title><path d="M320,256a64,64,0,1,1-64-64A64.07,64.07,0,0,1,320,256Zm189.81,9.42C460.86,364.89,363.6,426.67,256,426.67S51.14,364.89,2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.14,147.11,148.4,85.33,256,85.33s204.86,61.78,253.81,161.25A21.33,21.33,0,0,1,509.81,265.42ZM362.67,256A106.67,106.67,0,1,0,256,362.67,106.79,106.79,0,0,0,362.67,256Z"/></svg>
            <svg id="hideEyeSignin" class="toggle-password" xmlns="http://www.w3.org/2000/svg" width="25" style="display:none;" viewBox="0 0 512 512"><title>eye-disabled-glyph</title><path d="M409.84,132.33l95.91-95.91A21.33,21.33,0,1,0,475.58,6.25L6.25,475.58a21.33,21.33,0,1,0,30.17,30.17L140.77,401.4A275.84,275.84,0,0,0,256,426.67c107.6,0,204.85-61.78,253.81-161.25a21.33,21.33,0,0,0,0-18.83A291,291,0,0,0,409.84,132.33ZM256,362.67a105.78,105.78,0,0,1-58.7-17.8l31.21-31.21A63.29,63.29,0,0,0,256,320a64.07,64.07,0,0,0,64-64,63.28,63.28,0,0,0-6.34-27.49l31.21-31.21A106.45,106.45,0,0,1,256,362.67ZM2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.15,147.11,148.4,85.33,256,85.33a277,277,0,0,1,70.4,9.22l-55.88,55.88A105.9,105.9,0,0,0,150.44,270.52L67.88,353.08A295.2,295.2,0,0,1,2.19,265.42Z"/></svg>
          </div>
          <div class="inputBox remember-me">
            <input type="checkbox" name="remember_me" id="remember_me" <?php
              echo (isset($_COOKIE['remember_me']) && isset($_SESSION['remember_me_token']) && $_COOKIE['remember_me'] === $_SESSION['remember_me_token']) ? 'checked' : '';
              ?>>
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
        <form method="POST" action="./actions/member.php">
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
            <svg id="showEyeSignup" class="toggle-password" xmlns="http://www.w3.org/2000/svg" width="25" viewBox="0 0 512 512"><title>eye-glyph</title><path d="M320,256a64,64,0,1,1-64-64A64.07,64.07,0,0,1,320,256Zm189.81,9.42C460.86,364.89,363.6,426.67,256,426.67S51.14,364.89,2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.14,147.11,148.4,85.33,256,85.33s204.86,61.78,253.81,161.25A21.33,21.33,0,0,1,509.81,265.42ZM362.67,256A106.67,106.67,0,1,0,256,362.67,106.79,106.79,0,0,0,362.67,256Z"/></svg>
            <svg id="hideEyeSignup" class="toggle-password" xmlns="http://www.w3.org/2000/svg" width="25" style="display:none;" viewBox="0 0 512 512"><title>eye-disabled-glyph</title><path d="M409.84,132.33l95.91-95.91A21.33,21.33,0,1,0,475.58,6.25L6.25,475.58a21.33,21.33,0,1,0,30.17,30.17L140.77,401.4A275.84,275.84,0,0,0,256,426.67c107.6,0,204.85-61.78,253.81-161.25a21.33,21.33,0,0,0,0-18.83A291,291,0,0,0,409.84,132.33ZM256,362.67a105.78,105.78,0,0,1-58.7-17.8l31.21-31.21A63.29,63.29,0,0,0,256,320a64.07,64.07,0,0,0,64-64,63.28,63.28,0,0,0-6.34-27.49l31.21-31.21A106.45,106.45,0,0,1,256,362.67ZM2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.15,147.11,148.4,85.33,256,85.33a277,277,0,0,1,70.4,9.22l-55.88,55.88A105.9,105.9,0,0,0,150.44,270.52L67.88,353.08A295.2,295.2,0,0,1,2.19,265.42Z"/></svg>
          </div>
          <div class="inputBox">
            <input type="password" id="confirmpassword" name="confirmpassword" required> <i>Confirm Password</i>
            <svg id="showEyeSignupConfirm" class="toggle-password" xmlns="http://www.w3.org/2000/svg" width="25" viewBox="0 0 512 512"><title>eye-glyph</title><path d="M320,256a64,64,0,1,1-64-64A64.07,64.07,0,0,1,320,256Zm189.81,9.42C460.86,364.89,363.6,426.67,256,426.67S51.14,364.89,2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.14,147.11,148.4,85.33,256,85.33s204.86,61.78,253.81,161.25A21.33,21.33,0,0,1,509.81,265.42ZM362.67,256A106.67,106.67,0,1,0,256,362.67,106.79,106.79,0,0,0,362.67,256Z"/></svg>
            <svg id="hideEyeSignupConfirm" class="toggle-password" xmlns="http://www.w3.org/2000/svg" width="25" style="display:none;" viewBox="0 0 512 512"><title>eye-disabled-glyph</title><path d="M409.84,132.33l95.91-95.91A21.33,21.33,0,1,0,475.58,6.25L6.25,475.58a21.33,21.33,0,1,0,30.17,30.17L140.77,401.4A275.84,275.84,0,0,0,256,426.67c107.6,0,204.85-61.78,253.81-161.25a21.33,21.33,0,0,0,0-18.83A291,291,0,0,0,409.84,132.33ZM256,362.67a105.78,105.78,0,0,1-58.7-17.8l31.21-31.21A63.29,63.29,0,0,0,256,320a64.07,64.07,0,0,0,64-64,63.28,63.28,0,0,0-6.34-27.49l31.21-31.21A106.45,106.45,0,0,1,256,362.67ZM2.19,265.42a21.33,21.33,0,0,1,0-18.83C51.15,147.11,148.4,85.33,256,85.33a277,277,0,0,1,70.4,9.22l-55.88,55.88A105.9,105.9,0,0,0,150.44,270.52L67.88,353.08A295.2,295.2,0,0,1,2.19,265.42Z"/></svg>
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
    $(document).ready(function() {
      const toggleSignup = $('#toggle-signup');
      const toggleSignin = $('#toggle-signin');
      const togglePasswordSignin = $('#showEyeSignin, #hideEyeSignin');
      const togglePasswordSignup = $('#showEyeSignup, #hideEyeSignup, #showEyeSignupConfirm, #hideEyeSignupConfirm');
      const signinForm = $('.signin');
      const signupForm = $('.signup');;

      $(toggleSignup).click(function() {
        signinForm.hide();
        signupForm.css('display', 'flex');
      });

      $(toggleSignin).click(function() {
        signupForm.hide();
        signinForm.css('display', 'flex');
      });

      $(togglePasswordSignin).click(function() {
        if ($("#signinPassword").attr("type") === "password") {
          $("#signinPassword").attr("type", "text");
          $("#showEyeSignin").hide();
          $("#hideEyeSignin").show();
        } else {
          $("#signinPassword").attr("type", "password");
          $("#showEyeSignin").show();
          $("#hideEyeSignin").hide();
        }
      })

      $(togglePasswordSignup).click(function() {
        if ($("#password").attr("type") === "password") {
          $("#password").attr("type", "text");
          $("#confirmpassword").attr("type", "text");
          $("#showEyeSignup").hide();
          $("#showEyeSignupConfirm").hide();
          $("#hideEyeSignup").show();
          $("#hideEyeSignupConfirm").show();
        } else {
          $("#password").attr("type", "password");
          $("#confirmpassword").attr("type", "password");
          $("#showEyeSignup").show();
          $("#showEyeSignupConfirm").show();
          $("#hideEyeSignup").hide();
          $("#hideEyeSignupConfirm").hide();
        }
      })

      $(signupForm).hide();
    });
  </script>
</body>

</html>