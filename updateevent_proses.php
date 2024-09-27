<?php
include 'db.php';

if (isset($_POST['submit'])) {

  $idevent = $_POST['idevent'];
  $eventname = $_POST['name'];
  $eventdate = $_POST['date'];
  $eventdesc = $_POST['description'];

  $sql = "UPDATE event set name = ?, date = ?, description = ? where idevent =?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_Param("sssi", $eventname, $eventdate, $eventdesc, $idevent);

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: admin_homepage.php");
    exit();
  } else {
    $stmt->close();
    $conn->close();
    echo "Error updating event: " . $conn->error;
  }
} else {
  echo "Invalid request!";
}
