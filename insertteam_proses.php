<?php
session_start();
if (isset($_SESSION['error'])) {
  echo "<script>alert('" . $_SESSION['error'] . "');</script>";
  unset($_SESSION['error']);
}
include 'db.php';
include 'check_loggedin.php';

if (isset($_POST['submit']) && !isset($_POST['detailteam'])) {
  $idgame = $_POST['idgame'];
  $teamname = $_POST['name'];
  $gambar = $_FILES['gambar'];
  $ext = pathinfo($gambar['name'], PATHINFO_EXTENSION);

  if (!validateFile($ext)) {
    exit("admin_homepage.php"); 
  }

  $sql = "INSERT INTO team (idteam, idgame, name, extention) VALUES (NULL, ?, ?, ?)";
  $stmt2 = $conn->prepare($sql);
  $stmt2->bind_param("iss", $idgame, $teamname, $ext);

  if ($stmt2->execute()) {
    echo "Team inserted successfully!";
    $new_id = $stmt2->insert_id;
    $dst = "gambar/$new_id.$ext";
    move_uploaded_file($gambar['tmp_name'], $dst);
  } else {
    echo "Error: " . $stmt2->error;
  }

  $stmt2->close();
  $conn->close();
  exit();
} elseif (isset($_POST['submit']) && isset($_POST['detailteam'])) {
  $idteam = $_POST['idteam'];

  if (isset($_POST['idevent'])) {
    $sql = "INSERT INTO event_teams (idevent, idteam) VALUES (?, ?)";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("ii", $_POST['idevent'], $idteam);

    if ($stmt2->execute()) {
      echo "Team inserted successfully!";
    } else {
      echo "Error: " . $stmt2->error;
    }

    $stmt2->close();
    $conn->close();
    header("Location: admin_homepage.php?table=team&detail=event" . (isset($idteam) ? '&id=' . $idteam : ''));
    exit();
  } else if (isset($_POST['idachievement'])) {
    $sql = "INSERT INTO achievement (idteam, name, date, description) VALUES (?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("isss", $idteam, $_POST['name'], $_POST['date'], $_POST['description']);

    if ($stmt2->execute()) {
      echo "Team inserted successfully!";
    } else {
      echo "Error: " . $stmt2->error;
    }

    $stmt2->close();
    $conn->close();
    header("Location: admin_homepage.php?table=team&detail=achievement" . (isset($idteam) ? '&id=' . $idteam : ''));
    exit();
  }

  header("Location: admin_homepage.php?table=".$_GET['table']."&detail=".$_GET['detail']."&id=".$_GET['id']);
}

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
?>

<br>
<a href="admin_homepage.php">Back to Home</a>
