<?php
include 'db.php';

if (isset($_GET['id'])) {
    $idteam = $_GET['id'];

    // Fetch current team details
    $sql = "SELECT * FROM team WHERE idteam = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idteam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $teamName = $row['name'];
        $idgame = $row['idgame'];
    } else {
        echo "Team not found!";
        exit();
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "No team ID provided!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Team</title>
</head>
<body>
    <h2>Edit Team</h2>
    <form method="POST" action="editteam_proses.php">
        <input type="hidden" name="idteam" value="<?php echo $idteam; ?>">
        
        <label>Team Name:</label>
        <input type="text" name="teamName" value="<?php echo $teamName; ?>" required><br>

        <label>Game Name:</label>
        <select name="game" required>
        <option value="null">-</option>
        <?php
            include 'db.php';
            $sql2 = "SELECT * FROM game";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            
            while ($row2 = $result2->fetch_assoc()) {
                $gameId = $row2['idgame'];
                $gameName = $row2['name'];
                $selected = ($gameId == $idgame) ? 'selected' : ''; 
                echo "<option value='$gameId' $selected>$gameName</option>";
            }
            $stmt2->close();
            $conn->close();
        ?>
        </select><br>

        <input type="submit" name="submit" value="Update Team">
    </form>
</body>
</html>
