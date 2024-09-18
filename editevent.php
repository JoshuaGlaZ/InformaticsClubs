<?php
include 'db.php';

if(isset($_POST['id']))
{
    $idevent = $_POST['id'];

    $sql = "SELECT * from event where idevent = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idevent);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $eventname= $row['name'];
        $eventdate = $row['date'];
        $eventdesc = $row['description'];
    }
    else{  
        echo'event not found!';
        exit();
    }

    $stmt->close();
    $conn->close();
}
else{
    echo "No event ID provided!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
</head>
<body>
    <h2> Edit Event</h2>
    <form method="POST" action="editevent_proses.php">
        <input type="hidden" name="idevent" value="<?php echo $idevent;?>">

        <label>Event Name:</label>
        <input type="text" name="eventName" value="<?php echo $eventname?>" required><br>
        <label>Event Date:</label>
        <input type="date" name="eventDate" value="<?php echo $eventdate?>" required><br>
        <label>Event Description</label>
        <input type="text" name="eventDesc" value="<?php echo $eventdesc?>" required><br>

        <input type="submit" name="submit" value="Update Event">
    </form>
</body>
</html>