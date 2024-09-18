<?php
include'db.php';
if (isset($_POST['submit'])) 
{
    $eventname = $_POST['eventName'];
    $eventdate = $_POST['eventDate'];
    $eventdesc = $_POST['eventDescription'];

    $sql = "INSERT INTO event (name, date, description) values(?,?,?)";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("sss", $eventname, $eventdate, $eventdesc);
    if($stmt2->execute()){
        header("Location: after_login.php");
        exit();
        }
        else{
            echo "Error insertig". $stmt2->error;
        }
        $stmt2->close();
        $conn->close();
}
else
{
    echo "invalid Request";
}
?>