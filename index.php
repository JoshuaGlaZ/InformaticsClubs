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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
          <h2 class="card-title">Card Title</h2> <!-- php simpan nama team di variable   -->
          <p class="card-description">This is the description of the card. Here you can provide detailed information.</p>
          <button class="card-button proposalButton">Join</button>
        </div>
      </div>
    </div>
  <?php endif; ?>



  <!-- Proposal Modal -->
  <div id="proposalModal" class="modal" style="display:none">
    <div class="modal-content">
      <div class="modal-header">
        <span id="closeProposal" class="close">&times;</span>
        <h2>Join Team X</h2> <!--echo nama team di variable-->
      </div>
      <form id="proposalForm" action="join_proposal.php" method="POST">
        <input type="hidden" name="idmember" value="">
        <input type="hidden" name="idteam" value="">
        <div class="input-group">
          <label for="idteam">Why do you want to join?</b></label>
          <textarea type="text" name="description" style="height:260px" required></textarea>
        </div>
        <div class="modal-footer">
          <button class="close">Cancel</button>
          <input type="submit" name="submit" value="Join"></input>
        </div>
      </form>
    </div>
  </div>


  <div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
  </div>

  <script>
    $(document).ready(function() {
      $(".proposalButton").on("click", function() {
        $("#proposalModal").css("display", "block");
      });

      $(".close").on("click", function() {
        $(".modal").css("display", "none");
      });

      $(window).on("click", function(event) {
        const proposalModal = $("#proposalModal")[0];
        if (event.target === proposalModal) {
          $("#proposalModal").css("display", "none");
        }
      });
    });
  </script>
</body>

</html>