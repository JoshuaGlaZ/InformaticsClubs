<?php
include 'db.php';

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT username FROM member WHERE username = ? and password = ?");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
      echo "Sign in successful! Hi " . $username . "!";
  } else {
    session_start();
    $_SESSION['error'] = 'Invalid username or password';
    header("Location: login.php");
    exit();
  }

  $stmt->close();
  $conn->close();
}
?>