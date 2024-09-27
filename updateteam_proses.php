<?php
include 'db.php';

if (isset($_POST['submit'])) {
    $idteam = $_POST['idteam'];
    $teamName = $_POST['name'];
    $idgame = $_POST['idgame'];

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

    // header("Location: admin_homepage.php");
    // exit();
} else {
    echo "Invalid request!";
}
?>
