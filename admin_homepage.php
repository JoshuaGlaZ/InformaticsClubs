<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}


$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT profile FROM member WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profile);
$stmt->fetch();
$stmt->close();

if ($profile !== 'admin') {
  header("Location: login.php");
  exit();
}

$table = isset($_GET['table']) ? $_GET['table'] : 'team';
$allowed_tables = ['team', 'game', 'event', 'achievement', 'join_proposal'];
if (!in_array($table, $allowed_tables)) {
  $table = 'team';
}

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

$records_per_page = 3;

$offset = ($page - 1) * $records_per_page;

$sql = "SELECT COUNT(*) AS total FROM " . $table;
$stmt = $conn->prepare($sql);

if ($stmt) {
  $stmt->execute();
  $result = $stmt->get_result();
  $totaldata = $result->fetch_assoc()['total'];
} else {
  echo "Error preparing statement: " . $conn->error;
  exit;
}

$total_pages = ceil($totaldata / $records_per_page);

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
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
        width="15"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
        <path
          d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
      </svg>
      Log Out
    </a>
  </div>
  <div id="sidebar">
    <ul>
      <?php
      foreach ($allowed_tables as $t) {
        $active_class = ($t == $table) ? 'active' : '';
        echo '<li class="' . $active_class . '"><a href="admin_homepage.php?table=' . $t . '">' . ucwords(str_replace('_', ' ', $t)) . '</a></li>';
      }
      ?>
    </ul>
  </div>
  <div id="content">
    <div class="card" <?= ((isset($_GET['detail'])) ? 'hidden' : '') ?>>

      <div id="card-header">
        <h2><?php echo ucwords(str_replace('_', ' ', $table)); ?> Table</h2>
        <?php

        if (!isset($_GET['detail'])) {
          if ($table != 'join_proposal') {
            echo '<button id="insertButton">Add New ' . ucfirst($table) . '</button>';
          }
        }
        ?>

      </div>
      <div class="search-bar">
        <input type="text" placeholder="Search...">
        <div class="input-group">
          <button class="advance-dropdown"><span class="caret"></span></button>
          <button class="search">
            <svg class="magnifying-glass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
              width="14"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
              <path
                d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
            </svg>
          </button>
        </div>
      </div>
      <div class="table-responsive">
        <table>
          <?php
          $sql = "SELECT * FROM " . $table . " LIMIT ? OFFSET ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("ii", $records_per_page, $offset);
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
            echo "<th colspan='" . (($table == 'team') ? '4' : '2') . "'>Action</th>";
            echo '</tr>';

            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              foreach ($row as $key => $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
              }
              $id = $row['id' . $table];
              if ($table == 'team') {
                echo "<td class='action'><a class='detail' href='admin_homepage.php?table=" . $table . "&detail=achievement&id=" . $id . "'>Team Achievement</button></td>";
                echo "<td class='action'><a class='detail' href='admin_homepage.php?table=" . $table . "&detail=event&id=" . $id . "'>Team Event</button></td>";
              }

              if ($table == 'join_proposal') {
                echo "<td class='action'><form action='approveproposal.php' method='post'>
                  <input type='hidden' name='id' value='" . $id . "'>
                  <button type='submit' name='submit' class='approve' id='" . $id . "'>Approve</button>
                  </form></td>";
                echo "<td class='action'><form action='rejectproposal.php' method='post'>
                    <input type='hidden' name='id' value='" . $id . "'>
                    <button type='submit' name='submit' class='reject' id='" . $id . "'>Reject</button>
                    </form></td>";
              } else {
                echo "<td class='action'><button class='update' id='" . $id . "'>Edit</button></td>";
                echo "<td class='action'><form action='delete" . $table . ".php' method='get'>
                  <input type='hidden' name='id' value='" . $id . "'>
                  <button type='submit' class='delete' id='" . $id . "'>Delete</button>
                  </form></td>";
                echo "</tr>";
              }
            }
          } else {
            echo '<tr><td colspan="6">No records found.</td></tr>'; // Handle empty result set
          }
          $_SESSION["fields"] = $fields;
          ?>
        </table>
      </div>

      <div class="pagination-container">
        <div class="pagination-info">
          Showing <?php echo ($totaldata > 0) ? ($offset + 1) : 0; ?> to
          <?php echo min($offset + $records_per_page, $totaldata); ?> of <?php echo $totaldata; ?> entries
        </div>
        <div class="pagination-controls">
          <?php if ($page > 1): ?>
            <a href="admin_homepage.php?table=<?php echo htmlspecialchars($table); ?>&page=<?php echo $page - 1; ?>"
              class="prev">Previous</a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="admin_homepage.php?table=<?php echo htmlspecialchars($table); ?>&page=<?php echo $i; ?>"
              class="page-number <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
          <?php endfor; ?>

          <?php if ($page < $total_pages): ?>
            <a href="admin_homepage.php?table=<?php echo htmlspecialchars($table); ?>&page=<?php echo $page + 1; ?>"
              class="next">Next</a>
          <?php endif; ?>
        </div>
      </div>

    </div>
    <div class="card" <?= ((isset($_GET['detail'])) ? '' : 'hidden') ?>>
      <div id="card-header">
        <h2>Halaman <span><?php echo ucfirst($_GET['table']) . ' ' . ucfirst($_GET['detail'])  ?></span></h2>
        <button id="insertButton">Add New <?php echo ucfirst($_GET['table']) . ' ' . ucfirst($_GET['detail']) ?></button>
      </div>
      <div class="search-bar">
        <input type="text" placeholder="Search...">
        <div class="input-group">
          <button class="advance-dropdown"><span class="caret"></span></button>
          <button class="search">
            <svg class="magnifying-glass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
              width="14"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
              <path
                d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
            </svg>
          </button>
        </div>
      </div>
      <div class="table-responsive">
        <table>
          <?php
          $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
          $records_per_page = 3;
          $offset = ($page - 1) * $records_per_page;
          if (isset($_GET['detail'])) {
            if ($_GET['detail'] == 'event') {
              $sql_detail = "SELECT COUNT(*) AS total FROM team t 
                            LEFT JOIN event_teams et ON t.idteam = et.idteam
                            INNER JOIN event e ON et.idevent = e.idevent 
                            WHERE t.idteam =" . $_GET['id'];
            } else if ($_GET['detail'] == 'achievement') {
              $sql_detail = "SELECT COUNT(*) AS total FROM team t 
                            INNER JOIN game g ON t.idgame = g.idgame
                            LEFT JOIN achievement a ON t.idteam = a.idteam
                            WHERE t.idteam =" . $_GET['id'];
            }
            $stmt_detail = $conn->prepare($sql_detail);

            if ($stmt_detail) {
              $stmt_detail->execute();
              $result_detail = $stmt_detail->get_result();
              $totaldata_detail = $result_detail->fetch_assoc()['total'];
            } else {
              echo "Error preparing statement: " . $conn->error;
              exit;
            }
            $total_pages = ceil($totaldata_detail / $records_per_page);
          }
          $sql = '';

          if (isset($_GET['detail'])) {
            if ($_GET['detail'] == 'achievement') {
              # code...
              $sql = "SELECT t.idteam, t.name AS team_name,
          a.name AS achievement_name, a.description as achievement_desc, a.date AS achievement_date
          FROM team t 
          LEFT JOIN achievement a ON t.idteam = a.idteam
          WHERE t.idteam =" . $_GET['id'] . " LIMIT ? OFFSET ?";
            } elseif ($_GET['detail'] == 'event') {
              # code...
              $sql = 'SELECT t.idteam, t.name AS team_name, e.idevent as idevent,  e.name AS event_name, e.description as event_desc, e.date as held_on
                    FROM team t 
                    left JOIN event_teams et ON t.idteam = et.idteam
                    inner JOIN event e ON et.idevent = e.idevent where t.idteam =' . $_GET['id'] . " LIMIT ? OFFSET ?";
            }
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $records_per_page, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            echo "<tr>";

            if ($_GET['detail'] == 'achievement') {
              # code...
              echo "<th>ID Team</th>
                        <th>Team</th>
                        <th>Achievement</th>
                        <th>Achievement Description</th>
                        <th>Tanggal Achievement</th>";
            } else {
              echo "<th>ID Team</th>
                        <th>Team</th>
                        <th>Event</th>
                        <th>Event Description</th>
                        <th>Held On</th>
                        <th>Action</th>";
            }

            echo "</tr>";

            echo "<tbody>";

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {

                echo "<tr>";
                if ($_GET['detail'] == 'achievement') {
                  # code...
                  // $idteam = $row['idteam'];

                  echo "<td>" . $row['idteam'] . "</td>";
                  echo "<td>" . $row['team_name'] . "</td>";

                  echo "<td>" . $row['achievement_name'] . "</td>";
                  echo "<td>" . $row['achievement_desc'] . "</td>";
                  echo "<td>" . $row['achievement_date'] . "</td>";

                  // echo "<td><a href='editteam.php?id=$idteam' class='edit'>Edit</a></td>";
                  // echo "<td><a href='deleteteam.php?id=$idteam' class='delete'>Delete</a></td>";
                } else {
                  echo "<td>" . $row['idteam'] . "</td>";
                  echo "<td>" . $row['team_name'] . "</td>";
                  echo "<td>" . $row['event_name'] . "</td>";
                  echo "<td>" . $row['event_desc'] . "</td>";

                  echo "<td>" . $row['held_on'] . "</td>";
                  echo "<td class='action'><form action='deleteteam.php' method='get'>
                <input type='hidden' name='idteam' value='" . $row['idteam'] . "'>
                <input type='hidden' name='idevent' value='" . $row['idevent'] . "'>
                <button type='submit' class='delete' id='" . $row['idteam'] . "'>Delete</button>
                </form></td>";
                  // echo "<td><a href='deleteteam.php?idteam=".$row['idteam']."&idevent=".$row['idteam']."' class='delete'>Delete</a></td>";

                }

                echo "</tr>";
              }
            } else {
              echo '<tr><td colspan="6">No records found.</td></tr>'; // Handle empty result set
            }
            echo "</tbody>";
          }
          ?>
        </table>
      </div>
      <div class="pagination-container">
        <div class="pagination-info">
          Showing <?php echo ($totaldata_detail > 0) ? ($offset + 1) : 0; ?> to
          <?php echo min($offset + $records_per_page, $totaldata_detail); ?> of <?php echo $totaldata_detail; ?> entries
        </div>
        <div class="pagination-controls">
          <?php if ($page > 1): ?>
            <a href="admin_homepage.php?table=team&detail=<?php echo $_GET['detail']; ?>&id=<?php echo $_GET['id']; ?>&page=<?php echo $page - 1; ?>"
              class="prev">Previous</a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="admin_homepage.php?table=team&detail=<?php echo $_GET['detail']; ?>&id=<?php echo $_GET['id']; ?>&page=<?php echo $i; ?>"
              class="page-number <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
          <?php endfor; ?>

          <?php if ($page < $total_pages): ?>
            <a href="admin_homepage.php?table=team&detail=<?php echo $_GET['detail']; ?>&id=<?php echo $_GET['id']; ?>&page=<?php echo $page + 1; ?>"
              class="next">Next</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Insert Modal -->
  <div id="insertModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <span id="closeInsert" class="close">&times;</span>
        <h2>Add New <?php echo ucfirst($table) . ' ' . (isset($_GET['detail']) ? ucfirst($_GET['detail']) : ''); ?></h2>
      </div>
      <form id="insertForm" action="insert<?php echo $table ?>_proses.php" method="POST">
        <?php
        if (isset($_GET['detail'])) {
          $sql_team = "SELECT idteam, name
          FROM team 
          WHERE idteam =" . $_GET['id'];
          $stmt_team = $conn->prepare(query: $sql_team);
          $stmt_team->execute();
          $result_team = $stmt_team->get_result();
          $row_team =  $result_team->fetch_assoc();
        ?>
          <div class="input-group">
            <label for="idteam">Team Name: <b><?= ucwords($row_team['name']) ?></b></label>
            <input type="text" id="insert_idteam" name="idteam" required value='<?= ucwords($row_team['idteam']) ?>' readonly>
          </div>
          <?php
          if ($_GET['detail'] == 'event') {
            $sql_event_team = "SELECT idevent FROM event_teams WHERE idteam=" . $_GET['id'];
            $stmt_event_team = $conn->prepare(query: $sql_event_team);
            $stmt_event_team->execute();
            $result_event_team = $stmt_event_team->get_result();

            $eventid = "(";
            $totalRows = $result_event_team->num_rows; // Hitung total baris
            $currentRow = 0; // Inisialisasi penghitung

            while ($row_event_team =  $result_event_team->fetch_assoc()) {
              $currentRow++; // Tambah penghitung di setiap iterasi
              $eventid .= $row_event_team['idevent'];
              if ($currentRow < $totalRows) {
                $eventid .= ", ";
              }
            }

            $eventid = rtrim($eventid, ", ") . ")";

            $sql_event = "SELECT * FROM event where idevent not in " . $eventid;
            $stmt_event = $conn->prepare(query: $sql_event);
            $stmt_event->execute();
            $result_event = $stmt_event->get_result();
          ?>
            <div class="input-group">
              <label for="event_name">Event Name</label>
              <select name="idevent" id="event_name">
                <option value="">-- Pilih Event --</option>
                <?php
                while ($row_event =  $result_event->fetch_assoc()) {
                  echo '<option value="' . $row_event['idevent'] . '">' . $row_event['name'] . '</option>';
                }
                echo '<input type="hidden" name="detailteam" value="event"></input>';
                ?>
              </select>
            </div>
          <?php
          } // endif get detail event
          else if ($_GET['detail'] == 'achievement') {
            $sql_achievement_team = "SELECT idachievement, name, date, description FROM achievement LIMIT 1";
            $stmt_achievement_team = $conn->prepare(query: $sql_achievement_team);
            $stmt_achievement_team->execute();
            $result_achievement_team = $stmt_achievement_team->get_result();
            while ($row_achievement = $result_achievement_team->fetch_assoc()) {
              foreach ($row_achievement as $field => $value) {
                if ($field == 'idachievement') {
                  echo '<input type="hidden" id="insert_' . $field . '" name="' . $field . '" value="' . $value . '">';
                  echo '<input type="hidden" name="detailteam" value="achievement"></input>';
                } else {
                  echo '<div class="input-group">';
                  echo '<label for="' . $field . '">Achievement ' . ucfirst($field) . '</label>';

                  // Generate the appropriate input types based on the field
                  if ($field == 'date') {
                    echo '<input type="date" id="insert_' . $field . '" name="' . $field . '" required>';
                  } else if ($field == 'description') {
                    echo '<textarea id="insert_' . $field . '" name="' . $field . '" required>' . '</textarea>';
                  } else {
                    echo '<input type="text" id="insert_' . $field . '" name="' . $field . '" required>';
                  }

                  echo '</div>';
                }
              }
            }
          ?>
          <div class="input-group">
            <label>Gambar</label>
            <input type="file" name="gambar" accept="image/*"><br>
          </div>
        <?php
          }
        } else {
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
              if ($field == 'idgame') {
                $sql2 = "SELECT * FROM game";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                while ($row2 = $result2->fetch_assoc()) {
                  $gameId = $row2['idgame'];
                  $gameName = $row2['name'];
                  echo "<option value='$gameId'>$gameName</option>";
                }
              } else if ($field == 'idteam') {
                $sql2 = "SELECT * FROM team";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                while ($row2 = $result2->fetch_assoc()) {
                  $teamId = $row2['idteam'];
                  $teamName = $row2['name'];
                  echo "<option value='$teamId'>$teamName</option>";
                }
              }
              echo '</select>';
              $stmt2->close();
              $conn->close();
            } else {
              echo '<input type="text" id="insert_' . $field . '" name="' . $field . '" required>';
            }
            echo '</div>';
          }
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
              $sql2 = "SELECT * FROM game";
              $stmt2 = $conn->prepare($sql2);
              $stmt2->execute();
              $result2 = $stmt2->get_result();
              while ($row2 = $result2->fetch_assoc()) {
                $gameId = $row2['idgame'];
                $gameName = $row2['name'];
                echo "<option value='$gameId'>$gameName</option>";
              }
            } else if ($field == 'idteam') {
              $sql2 = "SELECT * FROM team";
              $stmt2 = $conn->prepare($sql2);
              $stmt2->execute();
              $result2 = $stmt2->get_result();
              while ($row2 = $result2->fetch_assoc()) {
                $teamId = $row2['idteam'];
                $teamName = $row2['name'];
                $selected = ($teamId == $_SESSION['fields'][1]) ? 'selected' : '';
                echo "<option value='$teamId' $selected>$teamName</option>";
              }
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
        row.find("td").not(".action").each(function() {
          var cellData = $(this).text();
          rowData.push(cellData); // Add the cell data to the rowData array
        });
        console.log(rowData)

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