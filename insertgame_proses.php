<?php
include 'db.php';

if (isset($_POST['submit'])) {
  $gamename = $_POST['name'];
  $description = $_POST['description'];

  // Assuming idgame is auto-incremented, use NULL for the first parameter
  $sql = "INSERT INTO game (name, description) VALUES (?, ?)";
  $stmt2 = $conn->prepare($sql);
  $stmt2->bind_param("ss", $gamename, $description);

  if ($stmt2->execute()) {
    echo "Game inserted successfully!";
  } else {
    echo "Error: " . $stmt2->error;
  }

  $stmt2->close();
  $conn->close();
  header("Location: admin_homepage.php");
  exit();
}
?>
<br>
<a href="admin_homepage.php">Back to Home</a>