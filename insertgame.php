<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Game</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data" action="insertgame_proses.php">

        <label>Game Name</label>
        <input type="text" name="gameName">
        <br>
        <label>Description</label>
        <input type="text" name="desc">
        <!-- <select name="game" id="game"> -->
        <?php
            // include 'db.php';

            // $sql = "SELECT * FROM game ";
            // $stmt = $conn->prepare($sql);
            // $stmt->execute();
            // $result = $stmt->get_result();

            // while($row = $result->fetch_assoc()) {
            //     $id = $row['idgame'];
            //     $name = $row['name'];

            //     echo "<option value='$id'>$name</option>";
            // }
            // $conn->close();
        ?>
        </select><br>
        <input type="submit" name="submit" value="Insert">

    </form>
</body>
</html>