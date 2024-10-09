<?php
include 'db.php';

print_r($_GET);
print_r($_POST);


if (isset($_POST['submit']) && !isset($_GET)) {
  $idgame = $_POST['idgame'];
  $teamname = $_POST['name'];

  // Assuming idteam is auto-incremented, use NULL for the first parameter
  $sql = "INSERT INTO team (idteam, idgame, name) VALUES (NULL, ?, ?)";
  $stmt2 = $conn->prepare($sql);
  $stmt2->bind_param("is", $idgame, $teamname);

  if ($stmt2->execute()) {
    echo "Team inserted successfully!";
  } else {
    echo "Error: " . $stmt2->error;
  }

  $stmt2->close();
  $conn->close();
  header("Location: admin_homepage.php");
  exit();
}
elseif (isset($_POST['submit']) && isset($_GET)) {
  # code...
  $idteam = $_POST['idteam'];
  $idevent = $_POST['idevent'];

  
  $sql = "INSERT INTO event_teams (idevent, idteam) VALUES (?, ?)";
  $stmt2 = $conn->prepare($sql);
  $stmt2->bind_param("ii", $idevent, $idteam);

  if ($stmt2->execute()) {
    echo "Team inserted successfully!";
  } else {
    echo "Error: " . $stmt2->error;
  }

  $stmt2->close();
  $conn->close();
  // header("Location: admin_homepage.php?table=".$_GET['table']."&detail=".$_GET['detail']."&id=".$_GET['id']);
  header("Location: admin_homepage.php?table=team&detail=event" . (isset($idteam) ? '&id=' . $idteam : ''));
  exit();
}
?>
  <br>
<a href="admin_homepage.php">Back to Home</a>