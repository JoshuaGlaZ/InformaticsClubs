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
    $stmt = $conn->prepare("INSERT INTO member (fname, lname, username, password, profile) VALUES (?, ?, ?, ?, 'member')");
    $stmt->bind_param("ssss", $firstname, $lastname, $username, $password);

    if ($stmt->execute()) {
      header("Location: after_login.php");
      exit();
    } else {
      echo "Error: " . $stmt->error;
    }
  }

  $stmt->close();
  $conn->close();
}
?>