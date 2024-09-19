<?php
session_start();

include 'db.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

echo "Welcome, " . $_SESSION['username'] . "!";

$sql = " SELECT t.idteam, t.name AS team_name, g.name AS game_name, g.description as game_desc ,a.name AS achievement_name
    FROM team t 
    INNER JOIN game g ON t.idgame = g.idgame
    LEFT JOIN achievement a ON t.idteam = a.idteam
    ";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border ='1'>";
echo "<tr>
            <th>ID Team</th>
            <th>Team</th>
            <th>Game</th>
            <th>Achievement</th>
            <th colspan = 2 >Action</th>
        </tr>";

// echo "<table border='1'";
//     echo "<tr>
//             <th>ID Game</th>
//             <th>Game Name</th>
//         </tr>";

while ($row = $result->fetch_assoc()) {
  echo "<tr>";
  $idteam = $row['idteam'];

  echo "<td>" . $idteam . "</td>";
  echo "<td>" . $row['team_name'] . "</td>";

  echo "<td>" . $row['game_name'] . "</td>";
  // echo "<td>";    
  //     $sql2 = "SELECT * 
  //     FROM detail_pemain as DP INNER JOIN pemain as p
  //     ON DP.idpemain=P.idpemain
  //     WHERE DP.idmovie=?";
  //     $stmt2 = $conn->prepare($sql2);
  //     $stmt2->bind_param("i", $idmovie);
  //     $stmt2->execute();
  //     $result2 = $stmt2->get_result();
  // echo "</td>";

  echo "<td>" . $row['achievement_name'] . "</td>";
  echo "<td><a href = 'editteam.php?id=$idteam'>Edit</td>";
  echo "<td><a href = 'deleteteam.php?id=$idteam'>Delete</td>";
  echo "</tr>";
}

$sql2 = "SELECT idgame, name AS game_name, description AS game_desc FROM game";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$result2 = $stmt2->get_result();

echo "<table border ='1'>";
echo "<tr>
            <th>ID Game</th>
            <th>Game Name</th>
            <th>Description</th>
            <th colspan = 2 >Action</th>
        </tr>";

while ($row2 = $result2->fetch_assoc()) {
  echo "<tr>";
  $idgame = $row2['idgame'];

  echo "<td>" . $idgame . "</td>";
  echo "<td>" . $row2['game_name'] . "</td>";
  echo "<td>" . $row2['game_desc'] . "</td>";
  // echo "<td>";    
  //     $sql2 = "SELECT * 
  //     FROM detail_pemain as DP INNER JOIN pemain as p
  //     ON DP.idpemain=P.idpemain
  //     WHERE DP.idmovie=?";
  //     $stmt2 = $conn->prepare($sql2);
  //     $stmt2->bind_param("i", $idmovie);
  //     $stmt2->execute();
  //     $result2 = $stmt2->get_result();
  // echo "</td>";
  echo "<td><a href = 'editgame.php?id=$idgame'>Edit</td>";
  echo "<td><a href = 'deletegame.php?id=$idgame'>Delete</td>";
  echo "</tr>";
}


$sql3 = "SELECT a.idachievement, t.name as team_name, a.name, a.date, a.description FROM achievement a inner join team t on t.idteam = a.idteam;";
$stmt3 = $conn->prepare($sql3);
$stmt3->execute();
$result3 = $stmt3->get_result();

echo "<table border ='1'>";
echo "<tr>
            <th>ID Achievement</th>
            <th>Team Name</th>
            <th>Achievement</th>
            <th>Date</th>
            <th>Description</th>
            <th colspan = 2 >Action</th>
        </tr>";

while ($row3 = $result3->fetch_assoc()) {
  echo "<tr>";
  $idachievement = $row3['idachievement'];

  echo "<td>" . $idachievement . "</td>";
  echo "<td>" . $row3['team_name'] . "</td>";
  echo "<td>" . $row3['name'] . "</td>";
  echo "<td>" . $row3['date'] . "</td>";
  echo "<td>" . $row3['description'] . "</td>";
  // echo "<td>";    
  //     $sql2 = "SELECT * 
  //     FROM detail_pemain as DP INNER JOIN pemain as p
  //     ON DP.idpemain=P.idpemain
  //     WHERE DP.idmovie=?";
  //     $stmt2 = $conn->prepare($sql2);
  //     $stmt2->bind_param("i", $idmovie);
  //     $stmt2->execute();
  //     $result2 = $stmt2->get_result();
  // echo "</td>";
  echo "<td><a href = 'editachievement.php?id=$idachievement'>Edit</td>";
  echo "<td><a href = 'deleteachievement.php?id=$idachievement'>Delete</td>";
  echo "</tr>";
}


?>

<a href="logout.php">Logout</a>
<br><br>
<a href="insertteam.php">Insert Team</a>
<br><br>
<a href="insertgame.php">Insert Game</a>