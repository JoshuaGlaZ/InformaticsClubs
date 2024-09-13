<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!";

echo "<table border ='1'>";
    echo "<tr>
            <th>ID</th>
            <th>Team</th>
            <th>Game</th>
            <th>Achievement</th>
            <th colspan = 2 >Action</th>
        </tr>";
?>

<a href="logout.php">Logout</a>
<br><br>
<a href="insertteam.php">Insert Team</a>