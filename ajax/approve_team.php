<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../sessions/check_loggedin.php';
require_once __DIR__ . '/../controllers/team.php';


$idmember = $_SESSION['idmember'];
$team = new Team();
$teams = $team->getApprovedTeams($idmember);

if (empty($teams)) {
  echo json_encode(["error" => "You are not approved for any team yet"]);
  exit;
} else {
  echo json_encode($teams);
}
?>
