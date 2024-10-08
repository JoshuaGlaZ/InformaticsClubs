<?php
session_start();
include 'db.php';

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT username FROM member WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($hash_pass);
    $stmt->fetch();
    $_SESSION['username'] = $username;
    if(password_verify($password, $hash_pass)){
      echo "Login Sukses!";
    }
    else{
      echo "Login gagal";
    }
    if (isset($_POST['remember_me'])) {
      $token = bin2hex(random_bytes(16));
      $hashedToken = hash('sha256', $token);
      setcookie('remember_me', $hashedToken, time() + (86400 * 30), "/"); // 30 days
      // setcookie('remember_me', $hashedToken, [
      //   'expires' => time() + (86400 * 30),
      //   'path' => '/',
      //   'secure' => true,  // Only send over HTTPS
      //   'httponly' => true,  // Prevent JavaScript access
      //   'samesite' => 'Strict'  // Prevent CSRF
      // ]);
      $_SESSION['remember_me_token'] = $hashedToken;
    }
    $stmt->close();
    $conn->close();
    header("Location: admin_homepage.php");
    exit();
  } else {
    $_SESSION['error'] = 'Invalid username or password';
    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
  }

}
