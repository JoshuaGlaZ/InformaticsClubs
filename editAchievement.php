<?php 
include 'db.php'; 
if (isset($_GET['id'])) {
    $idachieve = $_GET['id'];

    $sql ="SELECT * FROM achievement Where idachievement=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idachieve);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $achievename = $row['name'];
        $idteam = $row['idteam'];
        $achievedate = $row['date'];
        $achievedesk = $row['description'];
    }
    else {  
        echo"achievement not found!";
        exit();
    }
    $stmt->close();
    $conn ->close();
}
 else{
    echo "No team ID provided!";
    exit();
 }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Achievement</title>
    </head>
    <body>
    <h2>Edit Team</h2>
    <form method="POST" action="editachievement_proses.php">
        <input type ="hidden" name="idachievement" value="<?php echo $idachieve;?>">
        <label> Achievement Name: </label>
        <input type="text" name="achieveName" value="<?php echo $achievename; ?>" required><br>

        <label>Team Name:</label>
        <select name="team" required>
            <option value="null">-</option>
            <?php
            include 'db.php';
            $sql2 = "SELECT * From team";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            while ($row2 = $result2->fetch_assoc()) {
                $teamid = $row2["idteam"];
                $teamname = $row2["name"];
                $selected = ($idteam == $teamid) ? 'selected':'';
                echo "<option value='$teamid' $selected>$teamname</option>";

            }
            $stmt2->close();
            $conn->close();
            ?>
        </select><br>

        <label>Achievement Date </label>
        <input type="date" name="achievementdate" value="<?php echo $achievedate; ?>" required><br>
        <label>Achievement Description</label>
        <input type="text" name="achievementdesk" value="<?php echo $achievedesk ?>" required><br>

        <input type="submit" name="submit" value="Update achievement">
    </form>     
    </body>
    </html>

