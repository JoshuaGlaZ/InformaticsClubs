<?php
session_start();
include 'db.php';
include 'check_loggedin.php';

$idmember = $_SESSION['idmember'];
$sql = "SELECT p.*, t.name FROM join_proposal p INNER JOIN team t ON t.idteam = p.idteam WHERE p.idmember = $idmember AND p.status = 'approved'";  
$result = $conn->query($sql);

$teams = [];
while ($row = $result->fetch_assoc()) {
    $teams[] = $row;
}

if (empty($teams)) {
  echo json_encode(["error" => "You are not approved for any team yet"]);
  exit;
} else {
  echo json_encode($teams);
}
$conn->close();
?>
