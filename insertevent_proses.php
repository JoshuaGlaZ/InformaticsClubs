<?php
include 'db.php';
include 'check_loggedin.php';

if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $date = $_POST['date'];
  $description = $_POST['description'];

  $sql = "INSERT INTO event (name, date, description) values(?,?,?)";
  $stmt2 = $conn->prepare($sql);
  $stmt2->bind_param("sss", $name, $date, $description);
  if ($stmt2->execute()) {
    $stmt2->close();
    $conn->close();
    header("Location: admin_homepage.php?table=event");
    exit();
  } else {
    echo "Error insertig" . $stmt2->error;
  }
  $stmt2->close();
  $conn->close();
} else {
  echo "invalid Request";
}
