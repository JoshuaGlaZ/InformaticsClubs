<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Team</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data" action="insertteam_proses.php">
        <label>Team Name</label>
        <input type="text" name="teamName"><br>
        <label>Game Name</label><br>
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "esport";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Failed to connect to MySQL: " . $conn->connect_error);
              }
            else {
                "Connection Success";
            }

            $sql = "SELECT * FROM game ";
            $stmt = $mysqli->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
        ?>
    </form>
</body>
</html>