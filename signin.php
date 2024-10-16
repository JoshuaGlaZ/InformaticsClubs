<?php
session_start();
include 'db.php';

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT idmember, password, profile FROM member WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result( $idmember, $hash_pass, $profile);
    $stmt->fetch();

    if (password_verify($password, $hash_pass)) {
      $_SESSION['username'] = $username;
      $_SESSION['idmember'] = $idmember;
      if (isset($_POST['remember_me'])) {
        $token = bin2hex(random_bytes(16));
        $hashedToken = hash('sha256', $token);
        setcookie('remember_me', $hashedToken, time() + (86400 * 30), "/");
        $_SESSION['remember_me_token'] = $hashedToken;
      }
      if ($profile === 'member') {
        header("Location: index.php");
      } else {
        header("Location: admin_homepage.php");
      }
      $stmt->close();
      $conn->close();
      exit();
    } else {
      $_SESSION['error'] = 'Invalid username or password';
      $stmt->close();
      $conn->close();
      header("Location: login.php");
      exit();
    }
  } else {
    $_SESSION['error'] = 'Invalid username or password';
    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
  }
} else {
  header("Location: login.php");
} 
?>
