<?php
include 'db.php';

if(isset($_POST['id'])){
    $idevent = $_GET['id'];

    $sql = "DELETE FROM event where idevent =?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("", $idevent);
    
    if($stmt->execute()){
        header("Location: admin_homepage.php");
        exit();
    }
    else{
        echo"Error deleting team:" . $conn->error;
    }
    $stmt->close();
    $conn->close(); 

}
else{
    echo"No event ID provided!";
}
?>