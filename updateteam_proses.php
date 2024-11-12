<?php
session_start();
if (isset($_SESSION['error'])) {
  echo "<script>alert('" . $_SESSION['error'] . "');</script>";
  unset($_SESSION['error']);
}
include 'db.php';
include 'check_loggedin.php';

if (isset($_POST['submit'])) {
  $idteam = $_POST['idteam'];
  $teamName = $_POST['name'];
  $idgame = $_POST['idgame'];
  $gambar = $_FILES['gambar'];
  $ext = pathinfo($gambar['name'], PATHINFO_EXTENSION);

  if (!validateFile($ext)) {
    exit(); 
  }

  $sql = "UPDATE team SET name = ?, idgame = ?, extention WHERE idteam = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("siis", $teamName, $idgame, $idteam, $ext);

  if ($stmt->execute()) {
    echo "Team updated successfully!";
    $new_id = $stmt2->insert_id;
    $dst = "gambar/$new_id.$ext";
    move_uploaded_file($gambar['tmp_name'], $dst);
  } else {
    echo "Error updating team: " . $conn->error;
  }

  $stmt->close();
  $conn->close();

  header("Location: admin_homepage.php?table=team");
  function validateFile($ext) {
  if ($ext !== 'jpg' && $ext !== 'jpeg') {
    echo "<script>
            alert('Please upload a JPG or JPEG file.');
            window.location.href = 'admin_homepage.php';
          </script>";
    return false;
  }
  return true;
}
  exit();
} else {
  echo "Invalid request!";
}
