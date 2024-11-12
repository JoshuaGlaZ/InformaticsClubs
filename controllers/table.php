<?php
require_once __DIR__ . '/../config/db.php';

class DatabaseTable extends Database
{
  private $allowedTables = ['team', 'game', 'event', 'achievement', 'join_proposal'];

  public function __construct()
  {
    parent::__construct();
  }

  public function getTableData($table, $page = 1, $recordsPerPage = 3, $detail = '')
  {
    // Validate the table name
    if (!in_array($table, $this->allowedTables)) {
      $table = 'team';
    }

    // Calculate the offset for pagination
    $offset = ($page - 1) * $recordsPerPage;

    // Initialize variables for total records and total pages
    $totalRecords = 0;
    $totalPages = 0;

    // Check if detail is provided 
    if ($detail == 'event' || $detail == 'achievement') {
      // Define the SQL query based on the detail type
      if ($detail == 'event') {
        $sql_detail = "SELECT COUNT(*) AS total FROM team t 
                         LEFT JOIN event_teams et ON t.idteam = et.idteam
                         INNER JOIN event e ON et.idevent = e.idevent 
                         WHERE t.idteam = ?";
      } elseif ($detail == 'achievement') {
        $sql_detail = "SELECT COUNT(*) AS total FROM team t 
                         INNER JOIN game g ON t.idgame = g.idgame
                         LEFT JOIN achievement a ON t.idteam = a.idteam
                         WHERE t.idteam = ?";
      }

      // Prepare and execute the statement for getting total data
      $stmt_detail = $this->conn->prepare($sql_detail);
      if ($stmt_detail) {
        $stmt_detail->bind_param("i", $_GET['id']);
        $stmt_detail->execute();
        $result_detail = $stmt_detail->get_result();
        $totalRecords = $result_detail->fetch_assoc()['total'];
        $stmt_detail->close();
      } else {
        echo "Error preparing statement: " . $this->conn->error;
        exit;
      }

      // Calculate total pages for detail data
      $totalPages = ceil($totalRecords / $recordsPerPage);

      // Prepare the appropriate SQL for paginated data based on the detail type
      if ($detail == 'event') {
        $sql = "SELECT t.idteam, t.name AS team_name, e.idevent as idevent, 
                         e.name AS event_name, e.description as event_desc, 
                         e.date as held_on 
                  FROM team t 
                  LEFT JOIN event_teams et ON t.idteam = et.idteam 
                  INNER JOIN event e ON et.idevent = e.idevent 
                  WHERE t.idteam = ? 
                  LIMIT ? OFFSET ?";
      } elseif ($detail == 'achievement') {
        $sql = "SELECT t.idteam, t.name AS team_name, a.name AS achievement_name, 
                         a.description as achievement_desc, a.date AS achievement_date 
                  FROM team t 
                  LEFT JOIN achievement a ON t.idteam = a.idteam 
                  WHERE t.idteam = ? 
                  LIMIT ? OFFSET ?";
      }
    } else {
      // For general table data (non-detail)
      $totalRecords = $this->getTotalRecords($table);
      $totalPages = ceil($totalRecords / $recordsPerPage);

      $sql = "SELECT * FROM " . $table . " LIMIT ? OFFSET ?";
    }

    // Prepare and execute the SQL query for paginated data
    $stmt = $this->conn->prepare($sql);
    if ($detail) {
      $stmt->bind_param("iii", $_GET['id'], $recordsPerPage, $offset); // Add ID parameter for details
    } else {
      $stmt->bind_param("ii", $recordsPerPage, $offset); // Regular table data
    }
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return [
      'data' => $data,
      'totalPages' => $totalPages,
      'totalData' => $totalRecords,
      'currentPage' => $page
    ];
  }

  private function getTotalRecords($table)
  {
    $sql = "SELECT COUNT(*) AS total FROM " . $table;
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalRecords = $result->fetch_assoc()['total'];
    $stmt->close();

    return $totalRecords;
  }
}
