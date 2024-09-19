<?php 
    include 'db.php';

    if(isset($_POST['submit'])) {
        $gamename = $_POST['gameName'];
        $desc = $_POST['desc'];

        // Assuming idgame is auto-incremented, use NULL for the first parameter
        $sql = "INSERT INTO game (name, description) VALUES (?, ?)";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("ss", $gamename, $desc);
        
        if($stmt2->execute()) {
            echo "Game inserted successfully!";
        } else {
            echo "Error: " . $stmt2->error;
        }
        
        $stmt2->close();
        $conn->close();
    }
?>
<br>
<a href="after_login.php">Back to Home</a>