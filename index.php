<?php
require_once("controllers/team.php");
session_start();
// $sql = "SELECT * from team";
// $result = $conn->query($sql);
echo '<input type="hidden" id="secret_id" name="idmember" value="'. $_SESSION['idmember'] . '">';
$team = new Team();
$teams = $team->getTeams();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IC</title>
  <link rel="stylesheet" href="assets/index.css">
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
      <?php while ($row = $teams->fetch_assoc()): ?>
        <div class="card">
          <div class="img-block">
            <img src="gambar/<?php echo $row['idteam'] ?>.jpg" class="card-img">
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
      <form id="proposalForm" action="actions/proposal.php" method="POST">
        <input type="hidden" name="idmember" id="idmemberField">
        <input type="hidden" name="idteam" id="idteamField">
        <input type="hidden" name="join">
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
          url: 'ajax/approve_team.php',
          method: 'GET',
          success: function(data) {
            const teams = JSON.parse(data);
            if (teams.error) {
              $('#approved-team').append('<p>' + teams.error + '</p>');
            } else {
              teams.forEach(function(team) {
                const itemsPerPage = 3; // Number of items to display per page

                // Pagination function
                function paginate(items, containerId, page = 1) {
                  const start = (page - 1) * itemsPerPage;
                  const end = start + itemsPerPage;
                  const paginatedItems = items.slice(start, end);
                  const totalPages = Math.ceil(items.length / itemsPerPage);

                  // Generate items HTML
                  let itemsHTML = '';
                  paginatedItems.forEach(item => {
                    itemsHTML += `<div class="item">${item}</div>`;
                  });

                  // Generate pagination controls with numbered buttons
                  let paginationHTML = `<div class="pagination-container">`;
                  if (page > 1) {
                    paginationHTML += `<div class="pagination-controls prev"><a onclick="navigatePage('${containerId}', ${page - 1})" class="prev">Previous</a></div>`;
                  }

                  paginationHTML += `<div class="pagination-controls num">`;
                  for (let i = 1; i <= totalPages; i++) {
                    paginationHTML += `<a onclick="navigatePage('${containerId}', ${i})" class="page-number ${i === page ? 'active' : ''}">${i}</a>`;
                  }
                  paginationHTML += `</div>`;

                  if (page < totalPages) {
                    paginationHTML += `<div class="pagination-controls next"><a onclick="navigatePage('${containerId}', ${page + 1})" class="next">Next</a></div>`;
                  }
                  paginationHTML += `</div>`;

                  // Display items and pagination in the specified container
                  $(`#${containerId}`).html(itemsHTML + paginationHTML);
                }

                // Initial pagination setup
                function initPagination(items, containerId) {
                  paginate(items, containerId, 1);
                }

                // Function to navigate pages
                window.navigatePage = function(containerId, page) {
                  const items = JSON.parse($(`#${containerId}`).attr('data-items'));
                  paginate(items, containerId, page);
                };

                // Prepare data for pagination
                const membersHTML = team.members.map(member => `<div class="item">${member}</div>`);
                const eventsHTML = team.events.map(event => `<div class="item">${event}</div>`);
                const achievementsHTML = team.achievements.map(achievement => `<div class="item">${achievement}</div>`);

                // Accordion item with dynamically generated member, event, and achievement data
                var accordionItem = `
                  <div class="accordion">
                    <ul>
                      <div class="header">
                        <div class="img-block">
                          <img src="gambar/${team.idteam}.jpg" class="card-img">
                        </div>
                        <h1>${team.team_name}</h1>
                      </div>
                      <li>
                        <input type="checkbox" checked>
                        <i></i>
                        <h2>Team Members</h2>
                        <div class="accordion-list-item" id="members_${team.idteam}" data-items='${JSON.stringify(team.members)}'></div>
                      </li>
                      <li>
                        <input type="checkbox" checked>
                        <i></i>
                        <h2>Events</h2>
                        <div class="accordion-list-item" id="events_${team.idteam}" data-items='${JSON.stringify(team.events)}'></div>
                      </li>
                      <li>
                        <input type="checkbox" checked>
                        <i></i>
                        <h2>Achievements</h2>
                        <div class="accordion-list-item" id="achievements_${team.idteam}" data-items='${JSON.stringify(team.achievements)}'></div>
                      </li>
                    </ul>
                  </div>
                `;

                $('#approved-team').append(accordionItem);

                // Initialize pagination for each section
                initPagination(team.members, `members_${team.idteam}`);
                initPagination(team.events, `events_${team.idteam}`);
                initPagination(team.achievements, `achievements_${team.idteam}`);
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

        $content.toggleClass('active');
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