<?php
require_once("../controllers/game.php");

session_start();
try {
  $game = new Game();

  if (isset($_POST['submit'])) {
    if (isset($_POST['insert'])) {
      $name = $_POST['name'];
      $desc = $_POST['description'];

      $game->addGame($name, $desc);
      $_SESSION['success'] = "Game inserted successfully!";
      header("Location: ../admin_homepage.php?table=game");
    } else if (isset($_POST['update'])) {
      $idgame = $_POST['idgame'];
      $gameName = $_POST['name'];
      $desc = $_POST['description'];

      $game->updateGame($gameName, $desc, $idgame);
      $_SESSION['success'] = "Game updated successfully!";
      header("Location: ../admin_homepage.php?table=game");
    }
  } else if (isset($_GET['id'])) {
    $idgame = $_GET['id'];

    $game->deleteGame($idgame);
    $_SESSION['success'] = "Game " . $idgame . " deleted successfully!";
    header("Location: ../admin_homepage.php?table=game");
    exit();
  }
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  header("Location: ../admin_homepage.php?table=game");
  exit();
}
