<?php
session_start();
include 'db.php';

$sql = "SELECT * from team";
$result = $conn->query($sql);
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
      <?php while($row = $result ->fetch_assoc()):?>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team<?php echo $row['idteam']?>" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title"><?php echo $row['name'];?></h2> <!-- php simpan nama team di variable   -->
          <p class="card-description">Come and Join Team <?php echo $row['name'];?> and be the Champion of the World </p>
          <button class="card-button proposalButton" data-team-id="<?php echo $row['idteam']; ?>" data-team-name="<?php echo $row['name']; ?>">Join</button>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>



 <!-- Proposal Modal -->
<div id="proposalModal" class="modal" style="display:none">
  <div class="modal-content">
    <div class="modal-header">
      <span id="closeProposal" class="close">&times;</span>
      <h2>Join <span id="teamNameInModal"></span></h2> <!-- Echo team name here -->
    </div>
    <form id="proposalForm" action="join_proposal.php" method="POST">
      <input type="hidden" name="idmember" value="<?php echo $row['idmember']; ?>">
      <input type="hidden" name="idteam" id="idTeamField" value="<?php echo $row['idteam']; ?>"> <!-- Correct the id to match the JavaScript -->
      <div class="input-group">
        <label for="description">Why do you want to join?</label>
        <textarea name="description" style="height:260px" required></textarea>
      </div>
      <div class="modal-footer">
        <button class="close">Cancel</button>
        <input type="submit" name="submit" value="Join">
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
        var teamName = $(this).data("team-name");
        var teamID = $(this).data("team-id");

        $("#teamNameInModal").text(teamName);
        $("#idteamField").val(teamID);

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