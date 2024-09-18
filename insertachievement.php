<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert achievement</title>
</head>
<body>
<form method="POST" enctype="multipart/form-data" action="insertachievement_proses.php">
    <label>achievement Name</label>
    <input type="text" name="achievementName"><br>

    <label>achievement Date</label>
    <input type="date" name="achievementDate"><br>
    <label>achievement Desk</label>
    <input type="text" name="achievementDesk"><br>
    <label>Team Name</label>
        <select name="team" id="team">
        <option value="null">-</option>
        <?php
            include 'db.php';

            $sql = "SELECT * FROM team ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            while($row = $result->fetch_assoc()) {
                $id = $row['idteam'];
                $name = $row['name'];

                echo "<option value='$id'>$name</option>";
            }
            $conn->close();
        ?>
        </select><br>
    <input type="submit" name="submit" value="Insert">
    
</form>


</body>
</html>