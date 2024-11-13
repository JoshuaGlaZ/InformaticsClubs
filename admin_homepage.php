<?php
include __DIR__ . '/sessions/profile_check.php';
require_once __DIR__ . '/controllers/table.php';
require_once __DIR__ . '/controllers/modal.php';

if (isset($_SESSION['error'])) {
  echo "<script>alert('" . $_SESSION['error'] . "');</script>";
  unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
  echo "<script>alert('" . $_SESSION['success'] . "');</script>";
  unset($_SESSION['success']);
}

if (!isset($_SESSION['username'])) {
  header("Location: index.php");
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

$db = new DatabaseTable();
$tableData = $db->getTableData($table, $page);

$data = $tableData['data'];
$total_pages = $tableData['totalPages'];
$totaldata = $tableData['totalData'];
$currentPage = $tableData['currentPage'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/admin_homepage.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>IC Admin Panel</title>
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
      <div class="table-responsive">
        <table>
          <?php
          if ($data) {
            echo '<table>';
            echo '<tr>';

            $fields = [];
            foreach ($data[0] as $key => $value) {
              if ($key !== 'password') {
                echo '<th>' . htmlspecialchars($key) . '</th>';
                $fields[] = $key;
              }
            }
            echo "<th colspan='" . (($table == 'team') ? '4' : '2') . "'>Action</th>";
            echo '</tr>';

            foreach ($data as $row) {
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
                echo "<td class='action'><form action='/actions/proposal.php' method='post'>
                  <input type='hidden' name='id' value='" . $id . "'>
                  <input type='hidden' name='approve' >
                  <button type='submit' name='submit' class='approve' id='" . $id . "'>Approve</button>
                  </form></td>";
                echo "<td class='action'><form action='/actions/proposal.php' method='post'>
                    <input type='hidden' name='id' value='" . $id . "'>
                    <input type='hidden' name='reject' >
                    <button type='submit' name='submit' class='reject' id='" . $id . "'>Reject</button>
                    </form></td>";
              } else {
                echo "<td class='action'><button class='update' id='" . $id . "'>Edit</button></td>";
                echo "<td class='action'><form action='actions/" . $table . ".php' method='get'>
                  <input type='hidden' name='id' value='" . $id . "'>
                  <button type='submit' class='delete' id='" . $id . "'>Delete</button>
                  </form></td>";
                echo "</tr>";
              }
            }
          } else {
            '<tr><td colspan="6">No records found.</td></tr>';
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
        <a href="admin_homepage.php?table=team" class="back-button page-number">Back</a>
        <h2>Halaman <span><?php echo ucfirst($_GET['table']) . ' ' . ucfirst(isset($_GET['detail']) ? $_GET['detail'] : '')  ?></span></h2>
        <button id="insertButton">Add New <?php echo ucfirst($_GET['table']) . ' ' . ucfirst(isset($_GET['detail']) ? $_GET['detail'] : '') ?></button>
      </div>
      <div class="table-responsive">
        <table>
          <?php
          if (isset($_GET['detail'])) {
            $tableData = $db->getTableData($table, $page,  $records_per_page, $_GET['detail']);

            $data = $tableData['data'];
            $total_pages = $tableData['totalPages'];
            $totaldata = $tableData['totalData'];
            $currentPage = $tableData['currentPage'];

            echo "<tr>";

            if ($_GET['detail'] == 'achievement') {
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

            if (count($data)) {
              foreach ($data as $row) {
                echo "<tr>";
                if ($_GET['detail'] == 'achievement') {
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
              echo '<tr><td colspan="6">No records found.</td></tr>';
            }
            echo "</tbody>";
          }
          ?>
        </table>
      </div>
      <div class="pagination-container">
        <?php if (isset($_GET['detail'])): ?>
          <div class="pagination-info">
            Showing <?php echo ($totaldata > 0) ? ($offset + 1) : 0; ?> to
            <?php echo min($offset + $records_per_page, $totaldata); ?> of <?php echo $totaldata; ?> entries
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
        <?php endif; ?>
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
      <form id="insertForm" action="actions/<?php echo $table ?>.php" method="POST" enctype="multipart/form-data">
        <?php
        $modal = new Modal($table, isset($_GET['detail']) ? $_GET['detail'] : null, isset($_GET['id']) ? $_GET['id'] : null);

        $teamInfo = $modal->getTeamInfo();
        $eventInfo = $modal->getEventInfo();
        $achievementInfo = $modal->getAchievementInfo();
        $formFields = $modal->getFormFields();
        if ($teamInfo) {
          echo '<div class="input-group">';
          echo '<label for="idteam">Team Name: <b>' . ucwords($teamInfo['name']) . '</b></label>';
          echo '<input type="text" id="insert_idteam" name="idteam" required value="' . ucwords($teamInfo['idteam']) . '" readonly>';
          echo '</div>';

          // If event detail is selected, show event selection
          if ($_GET['detail'] == 'event' && $eventInfo) {
            echo '<div class="input-group">';
            echo '<label for="event_name">Event Name</label>';
            echo '<select name="idevent" id="event_name">';
            echo '<option value="">-- Pilih Event --</option>';
            while ($row_event = $eventInfo->fetch_assoc()) {
              echo '<option value="' . $row_event['idevent'] . '">' . $row_event['name'] . '</option>';
            }
            echo '<input type="hidden" name="detailteam" value="event"></input>';
            echo '</select>';
            echo '</div>';
          }

          // If achievement detail is selected, show achievement fields
          if ($_GET['detail'] == 'achievement' && $achievementInfo) {
            while ($row_achievement = $achievementInfo->fetch_assoc()) {
              foreach ($row_achievement as $field => $value) {
                if ($field == 'idachievement') {
                  echo '<input type="hidden" id="insert_' . $field . '" name="' . $field . '" value="' . $value . '">';
                  echo '<input type="hidden" name="detailteam" value="achievement"></input>';
                } else {
                  echo '<div class="input-group">';
                  echo '<label for="' . $field . '">Achievement ' . ucfirst($field) . '</label>';
                  if ($field == 'date') {
                    echo '<input type="date" id="insert_' . $field . '" name="' . $field . '" required>';
                  } else if ($field == 'description') {
                    echo '<textarea id="insert_' . $field . '" name="' . $field . '" required></textarea>';
                  } else {
                    echo '<input type="text" id="insert_' . $field . '" name="' . $field . '" required>';
                  }
                  echo '</div>';
                }
              }
            }
          }
        }

        // Default case for fields in the session
        if (!isset($_GET['id'])) {
          for ($i = 1; $i < count($formFields); $i++) {
            $field = $formFields[$i];
            echo '<div class="input-group">';
            if ($field == 'extention') $field = 'poster';  // Handle 'extention' case
            echo '<label for="' . $field . '"> ' . ucfirst($field) . ':</label>';

            if ($field == 'date') {
              echo '<input type="date" id="insert_' . $field . '" name="' . $field . '" required>';
            } else if ($field == 'description') {
              echo '<textarea id="insert_' . $field . '" name="' . $field . '" required></textarea>';
            } else if (str_starts_with($field, 'id')) {
              $fieldOptions = $modal->getFieldOptions($field);
              echo '<select id="insert_' . $field . '" name="' . $field . '" required>';
              while ($row2 = $fieldOptions->fetch_assoc()) {
                echo "<option value='" . $row2[$field] . "'>" . $row2['name'] . "</option>";
              }
              echo '</select>';
            } else if ($field == 'poster') {
              echo '<input type="file" id="insert_' . $field . '" name="gambar" accept="image/jpg">';
            } else {
              echo '<input type="text" id="insert_' . $field . '" name="' . $field . '" required>';
            }
            echo '</div>';
          }
        }
        ?>
        <div class="modal-footer">
          <button class="close">Cancel</button>
          <input type="hidden" name="insert">
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
      <form id="updateForm" action="actions/<?php echo $table ?>.php" method="POST" enctype="multipart/form-data">
        <?php
        echo '<div class="input-group">';
        $field = $_SESSION['fields'][0];

        echo '<label for="' . $field . '"> ' . ucfirst($field) . ':</label>';
        echo '<input type="text" id="update_' . $field . '" name="' . $field . '" required readonly>';

        for ($i = 1; $i < count($_SESSION['fields']); $i++) {
          $field = $_SESSION['fields'][$i];
          if ($field == 'extention') {
            $field = 'poster';
          }

          echo '<label for="' . $field . '"> ' . ucfirst($field) . ':</label>';
          if ($field == 'date') {
            echo '<input type="date" id="update_' . $field . '" name="' . $field . '" required>';
          } else if ($field == 'description') {
            echo '<textarea id="update_' . $field . '" name="' . $field . '" required>$value</textarea>';
          } else if (str_starts_with($field, 'id')) {
            echo '<select id="update_' . $field . '" name="' . $field . '" required>';
            $result2 = $modal->getFieldOptions($field);
            if ($field == 'idgame') {
              while ($row2 = $result2->fetch_assoc()) {
                $gameId = $row2['idgame'];
                $gameName = $row2['name'];
                echo "<option value='$gameId'>$gameName</option>";
              }
            } else if ($field == 'idteam') {
              while ($row2 = $result2->fetch_assoc()) {
                $teamId = $row2['idteam'];
                $teamName = $row2['name'];
                $selected = ($teamId == $_SESSION['fields'][1]) ? 'selected' : '';
                echo "<option value='$teamId' $selected>$teamName</option>";
              }
            }
            echo '</select>';
          } else if ($field == 'poster') {
            echo '<input type="file" id="update_' . $field . '" name="gambar" accept="image/jpg">';
          } else {
            echo '<input type="text" id="update_' . $field . '" name="' . $field . '" required>';
          }
        }
        echo '</div>';
        ?>
        <div class="modal-footer">
          <button class="close">Cancel</button>
          <input type="hidden" name="update">
          <input type="submit" name="submit" value="Save"></input>
        </div>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      console.log("Insert button clicked");

      $("#insertButton").on("click", function() {
        $("#insertModal").css("display", "block");
        console.log("Insert button clicked");
      });
      $(".update").on("click", function() {
        console.log("Update button clicked");
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

        $.ajax({
          type: "POST",
          url: "sessions/updateSession.php",
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
                const inputElement = $('#update_' + field);

                if (inputElement.length) {
                  inputElement.val(data.data[field] || '');
                } else {
                  console.error(`Field with ID '${field}' not found in the DOM.`);
                }
              });

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