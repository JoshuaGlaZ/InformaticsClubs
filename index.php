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

  <?php if (isset($_SESSION['username'])): ?>
    <div class="scrollable-panel">
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team1" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team2" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team3" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team4" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team3" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team5" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team6" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team7" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team8" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team9" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team10" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title">Card Title</h2>
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button">View Details</button>
        </div>
      </div>
    </div>
  <?php endif; ?>





  <div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
  </div>

</body>

</html>