<?php
include 'db.php';
include 'check_loggedin.php';

if (isset($_GET['id'])) {
  $idgame = $_GET['id'];

  $sql = "DELETE FROM game WHERE idgame = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $idgame);

  if ($stmt->execute()) {
    echo "Game deleted successfully!";
  } else {
    echo "Error deleting Game: " . $conn->error;
  }

  $stmt->close();
  $conn->close();

  header("Location: admin_homepage.php?table=game");
  exit();
} else {
  echo "No game ID provided!";
}
