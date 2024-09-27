<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$table = isset($_GET['table']) ? $_GET['table'] : 'team';
$allowed_tables = ['team', 'game', 'event', 'achievement'];
if (!in_array($table, $allowed_tables)) {
  $table = 'team';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="admin_homepage.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>Document</title>
</head>

<body>
  <div id="navbar">
    <span class="admin-panel">Admin Panel </span>
    <a class="logout-button" href="logout.php">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="15"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
      </svg>
      Log Out
    </a>
  </div>
  <div id="sidebar">
    <ul>
      <?php
      foreach ($allowed_tables as $t) {
        $active_class = ($t == $table) ? 'active' : '';
        echo '<li class="' . $active_class . '"><a href="admin_homepage.php?table=' . $t . '">' . ucfirst($t) . '</a></li>';
      }
      ?>
    </ul>
  </div>
  <div id="content">
    <div class="card">
      <div id="card-header">
        <h2><?php echo ucfirst($table); ?> Table</h2>
        <button id="insertButton">Add New <?php echo ucfirst($table); ?></button>
      </div>
      <div class="search-bar">
        <input type="text" placeholder="Search...">
        <div class="input-group">
          <button class="advance-dropdown"><span class="caret"></span></button>
          <button class="search">
            <svg class="magnifying-glass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
              <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
            </svg>
          </button>
        </div>
      </div>
      <div class="table-responsive">
        <table>
          <?php
          $sql = "SELECT * FROM " . $table . ";";
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $result = $stmt->get_result();
          $fields = [];
          if ($result->num_rows > 0) {
            echo '<tr>';
            while ($field = mysqli_fetch_field($result)) {
              if ($field->name !== 'password') {
                echo '<th>' . $field->name . '</th>';
                $fields[] = $field->name;
              }
            }
            echo "<th colspan='2'>Action</th>";
            echo '</tr>';

            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              foreach ($fields as $field_name) {
                echo "<td>" . htmlspecialchars($row[$field_name]) . "</td>";
              }
              $id = $row['id' . $table];
              echo "<td><button class='update' id='" . $id . "'>Edit</button></td>";
              echo "<td><form action='delete" . $table . ".php' method='get'>
              <input type='hidden' name='id' value='" . $id . "'>
              <button type='submit' class='delete' id='" . $id . "'>Delete</button>
              </form></td>";
              echo "</tr>";
            }
          } else {
            while ($field = mysqli_fetch_field($result)) {
              if ($field->name !== 'password') {
                echo '<th>' . $field->name . '</th>';
                $fields[] = $field->name;
              }
            }
            echo '<tr><td colspan="6">No records found.</td></tr>'; // Handle empty result set
          }
          $_SESSION["fields"] = $fields;
          ?>
        </table>
      </div>
      <div class="pagination-container">
        <div class="pagination-info">Showing 1 to 5 of 25 entries</div>
        <div class="pagination-controls">
          <a href="#" class="prev">Previous</a>
          <a href="#" class="page-number">1</a>
          <a href="#" class="page-number">2</a>
          <a href="#" class="page-number">3</a>
          <a href="#" class="page-number">4</a>
          <a href="#" class="next">Next</a>
        </div>
      </div>
    </div>
    <div class="card" hidden>
      <div id="card-header">
        <?php $table_name = 'A' ?>
        <h2>Table <span><?php echo $table_name ?></span></h2>
        <button class="insert">Add New <?php echo $table_name ?></button>
      </div>
      <div class="search-bar">
        <input type="text" placeholder="Search...">
        <div class="input-group">
          <button class="advance-dropdown"><span class="caret"></span></button>
          <button class="search">
            <svg class="magnifying-glass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
              <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
            </svg>
          </button>
        </div>
      </div>
      <div class="table-responsive">
        <table>
          <?php
          $sql = "SELECT t.idteam, t.name AS team_name, g.name AS game_name, g.description as game_desc ,a.name AS achievement_name
                  FROM team t 
                  INNER JOIN game g ON t.idgame = g.idgame
                  LEFT JOIN achievement a ON t.idteam = a.idteam";
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $result = $stmt->get_result();

          echo "<tr>
                  <th>ID Team</th>
                  <th>Team</th>
                  <th>Game</th>
                  <th>Achievement</th>
                  <th colspan = 2 >Action</th>
              </tr>";

          while ($row = $result->fetch_assoc()) {
            echo "<tbody>";
            echo "<tr>";
            $idteam = $row['idteam'];

            echo "<td>" . $idteam . "</td>";
            echo "<td>" . $row['team_name'] . "</td>";
            echo "<td>" . $row['game_name'] . "</td>";

            echo "<td>" . $row['achievement_name'] . "</td>";
            echo "<td><a href='editteam.php?id=$idteam' class='edit'>Edit</a></td>";
            echo "<td><a href='deleteteam.php?id=$idteam' class='delete'>Delete</a></td>";
            echo "</tr>";
            echo "</tbody>";
          }
          ?>
        </table>
      </div>
      <div class="pagination-container">
        <div class="pagination-info">
          Showing 5 out of 25 entries
        </div>
        <div class="pagination-controls">
          <a href="#" class="prev">Previous</a>
          <a href="#" class="page-number">1</a>
          <a href="#" class="page-number">2</a>
          <a href="#" class="page-number">3</a>
          <a href="#" class="page-number">4</a>
          <a href="#" class="page-number">5</a>
          <a href="#" class="next">Next</a>
        </div>
      </div>
    </div>

    <!-- Insert Modal -->
    <div id="insertModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <span id="closeInsert" class="close">&times;</span>
          <h2>Add New <?php echo ucfirst($table); ?></h2>
        </div>
        <form id="insertForm" action="insert<?php echo $table ?>_proses.php" method="POST">
          <?php
          for ($i = 1; $i < count($_SESSION['fields']); $i++) {
            echo '<div class="input-group">';
            $field = $_SESSION['fields'][$i];
            echo '<label for="' . $field . '"> ' . ucfirst($field) . ':</label>';
            if ($field == 'date') {
              echo '<input type="date" id="insert_' . $field . '" name="' . $field . '" required>';
            } else if ($field == 'description') {
              echo '<textarea id="insert_' . $field . '" name="' . $field . '" required></textarea>';
            } else if (str_starts_with($field, 'id')) {
              echo '<select id="insert_' . $field . '" name="' . $field . '" required>';
              $sql2 = "SELECT * FROM game";
              $stmt2 = $conn->prepare($sql2);
              $stmt2->execute();
              $result2 = $stmt2->get_result();

              while ($row2 = $result2->fetch_assoc()) {
                $gameId = $row2['idgame'];
                $gameName = $row2['name'];

                echo "<option value='$gameId'>$gameName</option>";
              }
              echo '</select>';
              $stmt2->close();
              $conn->close();
            } else {
              echo '<input type="text" id="insert_' . $field . '" name="' . $field . '" required>';
            }
            echo '</div>';
          }

          ?>
          <div class="modal-footer">
            <button class="close">Cancel</button>
            <input type="submit" name="submit" value="Save"></input>
          </div>
        </form>
      </div>
    </div>


    <!-- Update Modal -->
    <div id="updateModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <span id="updateClose" class="close">&times;</span>
          <h2>Update <?php echo ucfirst($table); ?> Data</h2>
        </div>
        <form id="updateForm" action="update<?php echo $table ?>_proses.php" method="POST">
          <?php
          include 'db.php';
          echo '<div class="input-group">';
          $field = $_SESSION['fields'][0];
          echo '<label for="' . $field . '"> ' . ucfirst($field) . ':</label>';
          echo '<input type="text" id="update_' . $field . '" name="' . $field . '" required readonly>';
          for ($i = 1; $i < count($_SESSION['fields']); $i++) {
            $field = $_SESSION['fields'][$i];
            echo '<label for="' . $field . '"> ' . ucfirst($field) . ':</label>';
            if ($field == 'date') {
              echo '<input type="date" id="update_' . $field . '" name="' . $field . '" required>';
            } else if ($field == 'description') {
              echo '<textarea id="update_' . $field . '" name="' . $field . '" required>$value</textarea>';
            } else if (str_starts_with($field, 'id')) {
              echo '<select id="update_' . $field . '" name="' . $field . '" required>';
              if ($field == 'idgame') {
                echo '<option value="null">-</option>';
              }
              $sql2 = "SELECT * FROM game";
              $stmt2 = $conn->prepare($sql2);
              $stmt2->execute();
              $result2 = $stmt2->get_result();

              while ($row2 = $result2->fetch_assoc()) {
                $gameId = $row2['idgame'];
                $gameName = $row2['name'];
                $selected = ($gameId ==  $_SESSION['fields'][0]) ? 'selected' : '';

                echo "<option value='$gameId' $selected>$gameName</option>";
              }
              echo '</select>';
              $stmt2->close();
              $conn->close();
            } else {
              echo '<input type="text" id="update_' . $field . '" name="' . $field . '" required>';
            }
          }
          echo '</div>';
          ?>
          <div class="modal-footer">
            <button class="close">Cancel</button>
            <input type="submit" name="submit" value="Save"></input>
          </div>
        </form>
      </div>
    </div>


  </div>


  <script>
    $(document).ready(function() {
      $("#insertButton").on("click", function() {
        $("#insertModal").css("display", "block");
      });

      $(".update").on("click", function() {
        var row = $(this).closest("tr");
        var totalColumns = row.find("td").length;
        var rowData = [];
        var fields = <?php echo json_encode($fields); ?>;

        // Collect each column data except the last two columns (edit & delete)
        row.find("td").slice(0, totalColumns - 2).each(function() {
          var cellData = $(this).text();
          rowData.push(cellData); // Add the cell data to the rowData array
        });

        // Loading
        $("body").css("cursor", "wait");

        // Send the row data to the server using AJAX
        $.ajax({
          type: "POST",
          url: "updateSession.php",
          data: {
            values: rowData
          },
          success: function(response) {
            $("body").css("cursor", "default");
            console.log("Session updated:", response);
            const data = JSON.parse(response);

            if (data.status === 'success') {
              const fields = <?php echo json_encode($fields); ?>;
              console.log(fields);
              $.each(fields, function(index, field) {
                const inputElement = $('#update_' + field); // Use jQuery to select the element

                if (inputElement.length) { // Check if the element exists
                  inputElement.val(data.data[field] || ''); // Set the value using jQuery
                } else {
                  console.error(`Field with ID '${field}' not found in the DOM.`);
                }
              });


              // Show the update modal
              $("#updateModal").css("display", "block");
            } else {
              console.error("Error in response:", data.message);
            }
          },

          error: function(xhr, status, error) {
            console.error("Error:", error);
            $("body").css("cursor", "default");
          }
        });
      });

      // Close the modal when the close button is clicked
      $(".close").on("click", function() {
        $(".modal").css("display", "none");
      });

      $(window).on("click", function(event) {
        const insertModal = $("#insertModal")[0];
        const updateModal = $("#updateModal")[0];
        if (event.target === insertModal) {
          $("#insertModal").css("display", "none");
        }
        if (event.target === updateModal) {
          $("#updateModal").css("display", "none");
        }
      })
    });
  </script>
</body>

</html>