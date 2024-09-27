<?php
include 'db.php';

if (isset($_POST['submit'])) {
  $idachievement = $_POST['idachievement'];
  $idteam = $_POST['idteam'];
  $namaachieve = $_POST['name'];
  $achievendate = $_POST['date'];
  $achievedesk = $_POST['description'];

  $sql = "UPDATE achievement SET  idteam = ?,  name = ?, date = ?, description = ? WHERE idachievement = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("isssi", $idteam, $namaachieve, $achievendate, $achievedesk, $idachievement);

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: admin_homepage.php");
    exit();
  } else {
    echo "Error updating" . $stmt->error;
    $stmt->close();
    $conn->close();
  }
} else {
  echo "Invalid Request";
}
