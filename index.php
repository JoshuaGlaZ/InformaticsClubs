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
      <input type="checkbox" id="nav-check">

      <div class="nav-btn">
        <label for="nav-check">
          <span></span>
          <span></span>
          <span></span>
        </label>
      </div>

      <ul class="nav-links">
        <li><a href="#" id="choose-team-link" class="active">Choose Team to Apply</a></li>
        <li><a href="#" id="approved-team-link">Application Status</a></li>

        <?php
        if (isset($_SESSION['username'])) {
          echo '<a href="logout.php"><button id="logout">Logout</button></a>';
        } else {
          echo '<a href="login.php"><button id="login">Login</button></a>';
        }
        ?>
      </ul>
    </div>
    </div>
  </nav>

  <?php if (isset($_SESSION['username'])): ?>
    <!-- Choose Team Section -->
    <div id="team-apply" class="scrollable-panel">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
          <div class="img-block">
            <img src="https://robohash.org/team<?php echo $row['idteam'] ?>" class="card-img">
          </div>
          <div class="card-content">
            <h2 class="card-title"><?php echo $row['name']; ?></h2> <!-- php simpan nama team di variable   -->
            <p class="card-description">Come and Join Team <?php echo $row['name']; ?> and be the Champion of the World </p>
            <button class="card-button proposalButton" data-member-id="<?php echo $_SESSION['idmember']; ?>"
              data-team-id="<?php echo $row['idteam']; ?>" data-team-name="<?php echo $row['name']; ?>">Join</button>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Approved Team Section -->
    <div id="approved-team" class="scrollable-panel" style="display: none;">
      <h2>Your Approved Team</h2>

    </div>
  <?php endif ?>

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
      $(".hamburger-menu").click(function() {
        $(".nav-links").toggleClass("show");
      });

      $('.scrollable-panel .card-content').each(function(index) {
        const delay = (0.1 * index) + 's';
        $(this).find('.card-title').css('--delay', delay);
        $(this).find('.card-description').css('--delay', delay);
      });

      $('#choose-team-link').on('click', function(event) {
        event.preventDefault();
        $(this).addClass('active');
        $('#approved-team-link').removeClass('active');
        $('#team-apply').show();
        $('#approved-team').hide();
      });

      $('#approved-team-link').on('click', function(event) {
        event.preventDefault();
        $(this).addClass('active');
        $('#choose-team-link').removeClass('active');
        $('#team-apply').hide();
        $('#approved-team').show().empty();
        $.ajax({
          url: 'approve_team.php',
          method: 'GET',
          success: function(data) {
            const teams = JSON.parse(data);
            if (teams.error) {
              $('#approved-team').append('<p>' + teams.error + '</p>');
            } else {
              teams.forEach(function(team) {
                var accordionItem = `
                  <div class="accordion">
                    <ul>
                      <div class="header">
                        <div class="img-block">
                          <img src="https://robohash.org/team${team.idteam}" class="card-img">
                        </div>
                        <h1>${team.name}</h1>
                      </div>
                      <li>
                        <input type="checkbox" checked>
                          <i></i>
                          <h2>Team Member</h2>
                          <div class="accordion-list-item">
                            <div class="item">Achievement 1</div>
                            <div class="item">Achievement 2</div>
                          </div>
                      </li>
                      <li>
                        <input type="checkbox" checked>
                          <i></i>
                          <h2>Events</h2>
                          <div class="accordion-list-item">
                            <div class="item">Achievement 1</div>
                            <div class="item">Achievement 2</div>
                          </div>
                      </li>
                      <li>
                        <input type="checkbox" checked>
                          <i></i>
                          <h2>Achievements</h2>
                          <div class="accordion-list-item">
                            <div class="item">Achievement 2</div>
                          </div>
                      </li>
                    </ul>
                  </div>
                  `;
                $('#approved-team').append(accordionItem);
              });
            }
          },
          error: function() {
            $('#approved-team').append('<p>Could not load approved teams. Please try again later.</p>');
          }
        });

        $('#approved-team').show();
      });


      function toggleAccordion(itemId) {
        var $content = $('#' + itemId);
        var $header = $content.prev('.accordion-header');

        // Toggle visibility of the content
        $content.toggleClass('active');

        // Toggle the arrow rotation
        $header.toggleClass('active');
      }

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