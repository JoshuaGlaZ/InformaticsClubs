<?php
include 'db.php';

if (isset($_POST['submit'])) {
    $idgame = $_POST['idgame'];
    $gameName = $_POST['gameName'];
    $desc = $_POST['desc'];

    $sql = "UPDATE game SET name = ?, description = ? WHERE idgame = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $gameName, $desc, $idgame);
    
    if ($stmt->execute()) {
        echo "Game updated successfully!";
    } else {
        echo "Error updating Game: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: after_login.php");
    exit();
} else {
    echo "Invalid request!";
}
?>
