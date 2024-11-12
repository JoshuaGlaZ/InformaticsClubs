<?php
require_once __DIR__ . '/../config/db.php';

class Modal extends Database
{
  private $table;
  private $detail;
  private $id;

  public function __construct($table, $detail = null, $id = null)
  {
    parent::__construct();
    $this->table = $table;
    $this->detail = $detail;
    $this->id = $id;
  }

  // Fetch team information
  public function getTeamInfo()
  {
    if (isset($this->id)) {
      $sql_team = "SELECT idteam, name FROM team WHERE idteam = ?";
      $stmt_team = $this->conn->prepare($sql_team);
      $stmt_team->bind_param("i", $this->id);
      $stmt_team->execute();
      return $stmt_team->get_result()->fetch_assoc();
    }
    return null;
  }

  // Fetch event options for a team
  public function getEventInfo()
  {
    if (isset($this->id)) {
      $sql_event_team = "SELECT idevent FROM event_teams WHERE idteam = ?";
      $stmt_event_team = $this->conn->prepare($sql_event_team);
      $stmt_event_team->bind_param("i", $this->id);
      $stmt_event_team->execute();
      $result_event_team = $stmt_event_team->get_result();

      $eventIds = [];
      while ($row_event_team = $result_event_team->fetch_assoc()) {
        $eventIds[] = $row_event_team['idevent'];
      }
      $eventid = "(" . implode(",", $eventIds) . ")";
      $sql_event = "SELECT * FROM event WHERE idevent NOT IN " . $eventid;
      $stmt_event = $this->conn->prepare($sql_event);
      $stmt_event->execute();
      return $stmt_event->get_result();
    }
    return null;
  }

  // Fetch achievement details
  public function getAchievementInfo()
  {
    if (isset($this->detail) && $this->detail == 'achievement') {
      $sql_achievement_team = "SELECT idachievement, name, date, description FROM achievement LIMIT 1";
      $stmt_achievement_team = $this->conn->prepare($sql_achievement_team);
      $stmt_achievement_team->execute();
      return $stmt_achievement_team->get_result();
    }
    return null;
  }

  // Get form fields based on session data
  public function getFormFields()
  {
    if (isset($_SESSION['fields']) && count($_SESSION['fields']) > 1) {
      return $_SESSION['fields'];
    }
    return [];
  }

  // Fetch data for specific field (e.g., games or teams)
  public function getFieldOptions($field)
  {
    if ($field == 'idgame') {
      $sql = "SELECT * FROM game";
    } else if ($field == 'idteam') {
      $sql = "SELECT * FROM team";
    } else if ($field == 'idevent') {
      $sql = "SELECT * FROM event";
    } else if ($field == 'idachievement') {
      $sql = "SELECT * FROM achievement";
    } else if ($field == 'idjoin_proposal') {
      $sql = "SELECT * FROM join_proposal";
    }
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
  }
}
