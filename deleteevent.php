<?php
include 'db.php';

if (isset($_GET['id'])) {
  $idevent = $_GET['id'];

  $sql = "DELETE FROM event where idevent =?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $idevent);

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: admin_homepage.php");
    exit();
  } else {
    echo "Error deleting team:" . $conn->error;
  }
  $stmt->close();
  $conn->close();
} else {
  echo "No event ID provided!";
}
