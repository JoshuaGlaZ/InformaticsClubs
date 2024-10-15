<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IC</title>
  <link rel="stylesheet" href="index.css">
</head>

<body>
  <nav class="navbar">
    <div class="navbar-content">
      <h1>Informatics Clubs</h1>
      <?php
      if (isset($_SESSION['username'])) {
        echo '<a href="logout.php"><button id="logout">Logout</button></a>';
      } else {
        echo '<a href="login.php"><button id="login">Login</button></a>';
      }
      ?>
    </div>
  </nav>
  <div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
  </div>
</body>

</html>