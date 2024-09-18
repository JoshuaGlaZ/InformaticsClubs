<?php
include 'db.php';

if(isset($_POST['id'])){
    $idachievement = $_GET['id'];

    $sql = "DELETE FROM achievement where idachievement = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idachievement);

    if ($stmt->execute()){ 
        header("Location: after_login.php");
        exit();
    }
    else{
        echo "Error deleting team: ". $conn->error;
    }

    $stmt->close();
    $conn->close();
}
else{
    echo"No team ID provided!";
}
?>