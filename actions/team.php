<?php
require_once("../controllers/team.php");

session_start();
try {
  $team = new Team();

  if (isset($_POST['submit'])) {
    if (isset($_POST['detailteam'])) {
      $idteam = $_POST['idteam'];
      if (isset($_POST['idevent'])) {
        $team->addEventToTeam($_POST['idevent'], $idteam);
        $_SESSION['success'] = "Event added to team successfully!";
        header("Location: ../admin_homepage.php?table=team&detail=event&id=" . $idteam);
      } elseif (isset($_POST['idachievement'])) {
        $team->addAchievementToTeam(
          $idteam,
          $_POST['name'],
          $_POST['date'],
          $_POST['description']
        );
        $_SESSION['success'] = "Achievement added to team successfully!";
        header("Location: ../admin_homepage.php?table=team&detail=achievement&id=" . $idteam);
      }
    } else if (isset($_POST['insert'])) {
      $idgame = $_POST['idgame'];
      $teamname = $_POST['name'];
      $image = $_FILES['gambar'];

      $team->addTeam($idgame, $teamname, $image);
      $_SESSION['success'] = "Team inserted successfully!";
      header("Location: ../admin_homepage.php");
    } else if (isset($_POST['update'])) {
      $idteam = $_POST['idteam'];
      $teamName = $_POST['name'];
      $idgame = $_POST['idgame'];
      $image = $_FILES['gambar'];

      $team->updateTeam($idteam, $teamName, $idgame, $image);
      $_SESSION['success'] = "Team updated successfully!";

      header("Location: ../admin_homepage.php?table=team");
    }
  } else if (isset($_GET['id'])) {
    $idteam = $_GET['id'];
    $team = new Team();

    $team->deleteTeam($idteam);
    $_SESSION['success'] = "Team " . $idteam . " deleted successfully!";

    header("Location: ../admin_homepage.php");
    exit();
  } else if (isset($_GET['idteam']) && isset($_GET['idevent'])) {
    $idteam = $_GET['idteam'];
    $idevent = $_GET['idevent'];
    $team = new Team();

    $team->deleteEventFromTeam($idteam, $idevent);
    $_SESSION['success'] = "Event " . $idevent . " for team " . $idteam . " deleted successfully!";

    header("Location: ../admin_homepage.php?table=team");
    exit();
  } else {
    throw new Exception("POST Error");
  }
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  header("Location: ../admin_homepage.php");
  exit();
}
