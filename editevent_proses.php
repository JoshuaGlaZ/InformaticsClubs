<?php
include 'db.php';

if(isset($_POST['submit'])){

    $idevent =$_POST['idevent'];
    $eventname = $_POST['eventName'];
    $eventdate = $_POST['eventDate'];
    $eventdesc = $_POST['eventDesc'];

    $sql ="UPDATE event set name = ?, date = ?, description = ? where idevent =?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_Param("sssi", $eventname, $eventdate, $eventdesc, $idevent );

    if($stmt->execute()){  
        header("Location: after_login.php");
        exit() ;
    }
    else{
    echo"Error updating event: ". $conn->error;
    }

    $stmt->close();
    $stmt->close();
}
else{
    echo "Invalid request!";
}
?>