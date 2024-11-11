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
      <ul>
        <li><a href="#" id="choose-team-link" class="active">Choose Team to Apply</a></li>
        <li><a href="#" id="application-status-link">Application Status</a></li>
      </ul>
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
    <!-- Choose Team Section -->
    <div id="team-apply" class="scrollable-panel">
      <?php while($row = $result ->fetch_assoc()):?>
      <div class="card">
        <div class="img-block">
          <img src="https://robohash.org/team<?php echo $row['idteam']?>" class="card-img">
        </div>
        <div class="card-content">
          <h2 class="card-title"><?php echo $row['name'];?></h2> <!-- php simpan nama team di variable   -->
          <p class="card-description">Come and Join Team <?php echo $row['name'];?> and be the Champion of the World </p>
          <button class="card-button proposalButton" data-member-id="<?php echo $_SESSION['idmember']; ?>" data-team-id="<?php echo $row['idteam']; ?>" data-team-name="<?php echo $row['name']; ?>">Join</button>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    
    <!-- Application Status Section (Hidden by Default) -->
    <div id="application-status" class="scrollable-panel" style="display: none;">
      <h2>Your Application Status</h2>
      <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Maxime mollitia magni quam esse architecto, blanditiis reiciendis pariatur repudiandae vitae saepe cumque tempore deleniti exercitationem amet est maiores iure magnam natus.</p>
    </div>
  <?php endif; ?>



 <!-- Proposal Modal -->
<div id="proposalModal" class="modal" style="display:none">
  <div class="modal-content">
    <div class="modal-header">
      <span id="closeProposal" class="close">&times;</span>
      <h2>Join <span id="teamNameInModal"></span></h2> 
    </div>
    <form id="proposalForm" action="join_proposal.php" method="POST">
      <input type="hidden" name="idmember" id="idmemberField">
      <input type="hidden" name="idteam" id="idteamField">
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
      $('#choose-team-link').on('click', function(event) {
        event.preventDefault();
        $(this).addClass('active');
        $('#application-status-link').removeClass('active');
        $('#team-apply').show();
        $('#application-status').hide();
      });

      $('#application-status-link').on('click', function(event) {
        event.preventDefault();
        $(this).addClass('active');
        $('#choose-team-link').removeClass('active');
        $('#team-apply').hide();
        $('#application-status').show();
      });

      $(".proposalButton").on("click", function() {
        var teamName = $(this).data("team-name");
        var teamID = $(this).data("team-id");
        var memberID = $(this).data("member-id");

        $("#teamNameInModal").text(teamName);
        $("#idteamField").val(teamID);
        $("#idmemberField").val(memberID);

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