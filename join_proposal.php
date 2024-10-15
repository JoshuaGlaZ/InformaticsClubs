<?php
include 'db.php';

if (isset($_POST['submit'])) {
  $idmember = $_POST['idmember'];
  $idteam = $_POST['idteam'];
  $description = $_POST['description'];

  $sql = "INSERT INTO event (idmember, idteam, description, status) values(?,?,?, 'waiting')";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iis", $idmember,  $idteam, $description);
  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: index.php");
    exit();
  } else {
    echo "Error insert proposal" . $stmt->error;
  }
  $stmt->close();
  $conn->close();
} else {
  echo "invalid Request";
}
