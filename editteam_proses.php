<?php
include 'db.php';

if (isset($_POST['submit'])) {
    $idteam = $_POST['idteam'];
    $teamName = $_POST['teamName'];
    $idgame = $_POST['game'];

    $sql = "UPDATE team SET name = ?, idgame = ? WHERE idteam = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $teamName, $idgame, $idteam);
    
    if ($stmt->execute()) {
        echo "Team updated successfully!";
    } else {
        echo "Error updating team: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: after_login.php");
    exit();
} else {
    echo "Invalid request!";
}
?>
