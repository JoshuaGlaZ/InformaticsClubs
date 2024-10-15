<?php
session_start();
include 'db.php';
include 'check_loggedin.php';

if (isset($_GET['id'])) {
  $idevent = $_GET['id'];

  $sql = "DELETE FROM event where idevent =?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $idevent);

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: admin_homepage.php?table=event");
    exit();
  } else {
    echo "Error deleting team:" . $conn->error;
  }
  $stmt->close();
  $conn->close();
} else {
  echo "No event ID provided!";
}
