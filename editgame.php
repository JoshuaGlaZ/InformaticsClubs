<?php
include 'db.php';

if (isset($_GET['id'])) {
    $idgame = $_GET['id'];

    $sql = "SELECT * FROM game WHERE idgame = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idgame);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $gameName = $row['name'];
        $idgame = $row['idgame'];
        $desc = $row['description'];
    } else {
        echo "Game not found!";
        exit();
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "No game ID provided!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game</title>
</head>
<body>
    <h2>Edit Game</h2>
    <form method="POST" action="editgame_proses.php">
        <input type="hidden" name="idgame" value="<?php echo $idgame; ?>">
        
        <label>Game Name:</label>
        <input type="text" name="gameName" value="<?php echo $gameName; ?>" required><br>

        <label>Description:</label>
        <input type="text" name="desc" value="<?php echo $desc; ?>" required><br>

        <input type="submit" name="submit" value="Update Game">
    </form>
</body>
</html>
