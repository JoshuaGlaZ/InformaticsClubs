<?php
session_start();
include 'db.php';
include 'check_loggedin.php';
  
if (isset($_POST['submit'])) {
    $idjoin_proposal = $_POST['id'];
    $sql = "UPDATE join_proposal SET status = 'approved' WHERE idjoin_proposal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$idjoin_proposal);
    
    if ($stmt->execute()) {
        echo "Proposal updated successfully!";
    } else {
        echo "Error updating Proposal: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: admin_homepage.php?table=join_proposal");
    exit();
} else {
    echo "Invalid request!";
}
?>
