<?php
include 'db.php';

if (isset($_GET['id'])) {
    $idteam = $_GET['id'];

    $sql = "DELETE FROM team WHERE idteam = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idteam);
    
    if ($stmt->execute()) {
        echo "Team deleted successfully!";
    } else {
        echo "Error deleting team: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: admin_homepage.php");
    exit();
} else {
    echo "No team ID provided!";
}
?>
