<?php
include 'db.php';

if (isset($_POST['submit'])) {
  $teamid = $_POST['idteam'];
  $achievename = $_POST['name'];
  $achievedate = $_POST['date'];
  $achievedesk = $_POST['description'];

  $sql = "INSERT INTO achievement (idachievement, idteam, name, date, description) VALUES(NULL, ?,?,?,?)";
  $stmt2 = $conn->prepare($sql);
  // Mengambil tanggal saat ini
  //$achievedate = date('Y-m-d'); // Format tanggal sesuai kebutuhan
  $stmt2->bind_param("isss", $teamid, $achievename, $achievedate, $achievedesk);

  if ($stmt2->execute()) {
    $stmt2->close();
    $conn->close();
    header("Location: admin_homepage.php");
    exit();
  } else {
    echo "Error updating" . $stmt2->error;
  }
  $stmt2->close();
  $conn->close();
} else {
  echo "invalid Request";
}
