<?php
include 'db.php';
include 'check_loggedin.php';

if (isset($_GET['id'])) {
  $idachievement = $_GET['id'];

  $sql = "DELETE FROM achievement where idachievement = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $idachievement);

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: admin_homepage.php?table=achievement");
    exit();
  } else {
    echo "Error deleting team: " . $conn->error;
  }

  $stmt->close();
  $conn->close();
} else {
  echo "No team ID provided!";
}
