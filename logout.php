<?php
session_start();
session_unset(); 
session_destroy();

// Remove the "Remember Me" cookie
if (isset($_COOKIE['remember_me'])) {
  setcookie('remember_me', '', time() - 3600, "/"); // Deleting the cookie
}

header("Location: login.php");
exit();
?>
