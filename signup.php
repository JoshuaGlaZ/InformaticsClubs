<?php
session_start();
include 'db.php';

if (isset($_POST['submit'])) {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT idmember FROM member WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $_SESSION['error'] = 'Username already taken';
    header("Location: login.php");
    exit();
  } else {
    $hash_pass = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO member (fname, lname, username, password, profile) VALUES (?, ?, ?, ?, 'member')");
    $stmt->bind_param("ssss", $firstname, $lastname, $username, $hash_pass);

    if ($stmt->execute()) {
      header("Location: admin_homepage.php");
      exit();
    } else {
      echo "Error: " . $stmt->error;
    }
  }

  $stmt->close();
  $conn->close();
} else {
  header("Location: login.php");
}
?>