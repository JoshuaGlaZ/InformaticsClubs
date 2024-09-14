<?php
session_start();

include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!";

echo "<table border ='1'>";
    echo "<tr>
            <th>ID</th>
            <th>Team</th>
            <th>Game</th>
            <th>Achievement</th>
            <th colspan = 2 >Action</th>
        </tr>";

$sql = " SELECT t.idteam, t.name AS team_name, g.name AS game_name, a.name AS achievement_name
    FROM team t 
    INNER JOIN game g ON t.idgame = g.idgame
    LEFT JOIN achievement a ON t.idteam = a.idteam
    "; 
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    echo "<tr>";
        $idteam = $row['idteam'];

        echo "<td>".$idteam."</td>";
        echo "<td>".$row['team_name']."</td>";

        echo "<td>".$row['game_name']."</td>";
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
        
        echo "<td>".$row['achievement_name']."</td>";
        echo "<td><a href = 'editteam.php?id=$idteam'>Edit</td>";
        echo "<td><a href = 'deleteteam.php?id=$idteam'>Delete</td>";
    echo "</tr>";
}

?>

<a href="logout.php">Logout</a>
<br><br>
<a href="insertteam.php">Insert Team</a>