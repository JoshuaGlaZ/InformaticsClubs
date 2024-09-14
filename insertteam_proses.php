<?php 
    include 'db.php';

    if(isset($_POST['submit'])) {
        $teamname = $_POST['teamName'];
        $idgame = $_POST['game'];

        // Assuming idteam is auto-incremented, use NULL for the first parameter
        $sql = "INSERT INTO team (idteam, idgame, name) VALUES (NULL, ?, ?)";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("is", $idgame, $teamname);
        
        if($stmt2->execute()) {
            echo "Team inserted successfully!";
        } else {
            echo "Error: " . $stmt2->error;
        }
        
        $stmt2->close();
        $conn->close();
    }
?>
<br>
<a href="after_login.php">Back to Home</a>
