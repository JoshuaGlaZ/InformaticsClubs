<?php 
    include 'db.php';
    if(isset($_POST['submit'])) {
        $teamname = $_POST['teamName'];
        $idgame = $_POST['game'];
        $name = $_POST['name'];

        $sql = "INSERT INTO team VALUES(?,?,?)";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("iis", $new_id, $idgame, $teamname);
        foreach ($idgame as $name) {
            $stmt2->execute();
        }
    }
?>
<br>
<a href="after_login.php">Back to Home</a>