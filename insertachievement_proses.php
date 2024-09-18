<?php
    include 'db.php';

    if(isset($_POST['submit'])){
        $achievename = $_POST['achievementName'];
        $teamid = $_POST['team'];
        $achievedate = $_POST['achievementDate'];
        $achievedesk = $_POST['achievementDesk'];

        $sql = "INSERT INTO achievement (idachievement, idteam, name, date, description) VALUES(NULL, ?,?,?,?)";
        $stmt2 = $conn->prepare($sql);
        // Mengambil tanggal saat ini
        //$achievedate = date('Y-m-d'); // Format tanggal sesuai kebutuhan
        $stmt2->bind_param("isss", $teamid, $achievename, $achievedate, $achievedesk);

        if($stmt2->execute()){
            header("Location: after_login.php");
            exit();
            }
            else{
                echo "Error updating". $stmt2->error;
            }
            $stmt2->close();
            $conn->close();
        
    }
    else{
        echo"invalid Request";
    }
?>