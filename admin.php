<?php
// session_start();
// if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
//     header('Location: login.php');
//     exit();
// }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fsp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete_team'])) {
    $team_id = intval($_GET['delete_team']);
    $delete_sql = "DELETE FROM team WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $team_id);
    if ($stmt->execute()) {
        echo "<p>Team deleted successfully.</p>";
    } else {
        echo "<p>Error deleting team: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

$sql = "SELECT * FROM team";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Informatics eSport Club</title>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Home</a></li>
                <li><a href="teams.php">Manage Teams</a></li>
                <li><a href="games.php">Manage Games</a></li>
                <li><a href="events.php">Manage Events</a></li>
                <li><a href="achievements.php">Manage Achievements</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Manage Teams</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Team Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['team_name']}</td>
                                <td>
                                    <a href='admin_dashboard.php?delete_team={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this team?\");'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No teams found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Informatics eSport Club Management System</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
