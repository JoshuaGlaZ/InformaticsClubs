<?php
session_start();
session_destroy();

// Remove the "Remember Me" cookie
setcookie('remember_me', '', time() - 3600, "/"); // Expire the cookie

header("Location: login.php");
exit();
?>
