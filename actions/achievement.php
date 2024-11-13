<?php
require_once("../controllers/achievement.php");

session_start();
try {
  $achievement = new Achievement();

  if (isset($_POST['submit'])) {
    if (isset($_POST['insert'])) {
      $teamid = $_POST['idteam'];
      $achievename = $_POST['name'];
      $achievedate = $_POST['date'];
      $achievedesk = $_POST['description'];

      $achievement->addAchievement($teamid, $achievename, $achievedate, $achievedesk);
      $_SESSION['success'] = "Achievement inserted successfully!";
      header("Location: ../admin_homepage.php?table=achievement");
    } else if (isset($_POST['update'])) {
      $idachievement = $_POST['idachievement'];
      $idteam = $_POST['idteam'];
      $namaachieve = $_POST['name'];
      $achievendate = $_POST['date'];
      $achievedesk = $_POST['description'];

      $achievement->updateAchievement($idteam, $namaachieve, $achievendate, $achievedesk, $idachievement);
      $_SESSION['success'] = "Achievement updated successfully!";
      header("Location: ../admin_homepage.php?table=achievement");
    }
  } else if (isset($_GET['id'])) {
    $idachievement = $_GET['id'];

    $achievement->deleteAchievement($idachievement);
    $_SESSION['success'] = "Achievement " . $idachievement . " deleted successfully!";
    header("Location: ../admin_homepage.php?table=achievement");
    exit();
  }
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  header("Location: ../admin_homepage.php?table=achievement");
  exit();
}
