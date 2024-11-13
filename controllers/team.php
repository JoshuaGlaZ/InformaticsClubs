<?php
require_once __DIR__ . '/../config/db.php';

class Team extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function addTeam($idgame, $teamname, $image)
  {
    if (!isset($image['name']) || is_array($image['name'])) {
      throw new Exception("Please upload only one image file.");
      header("Location: ../admin_homepage.php");
      exit();
  } 
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    if (!$this->validateFile($ext)) {
      throw new Exception("Please upload a JPG or JPEG file");
      header("Location: ../admin_homepage.php");
      exit();
    }
    $sql = "INSERT INTO team (idteam, idgame, name, extention) VALUES (NULL, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iss", $idgame, $teamname, $ext);

    if ($stmt->execute()) {
      $new_id = $stmt->insert_id;
      $destination = "../gambar/$new_id.$ext";
      move_uploaded_file($image['tmp_name'], $destination);
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error inserting team: " . $stmt->error);
    }
  }

  public function addEventToTeam($eventId, $teamId)
  {
    $sql = "INSERT INTO event_teams (idevent, idteam) VALUES (?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $eventId, $teamId);

    if (!$stmt->execute()) {
      $stmt->close();
      throw new Exception("Error inserting event to team: " . $stmt->error);
    }
    $stmt->close();
  }

  public function addAchievementToTeam($teamId, $name, $date, $description)
  {
    $sql = "INSERT INTO achievement (idteam, name, date, description) VALUES (?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("isss", $teamId, $name, $date, $description);

    if (!$stmt->execute()) {
      $stmt->close();
      throw new Exception("Error inserting achievement to team: " . $stmt->error);
    }
    $stmt->close();
  }

  public function getTeams()
  {
    $sql = "SELECT * from team";
    $result = $this->conn->query($sql);
    return $result;
  }

  public function getApprovedTeams($idmember)
  {
    $sql = "
    SELECT 
        jp.idteam, 
        t.name as team_name, 
        g.name as game_name, 
        CONCAT(m.fname, ' ', m.lname) as member_fullname,
        e.name as event_name, 
        a.name as achievement_name 
    FROM 
        join_proposal jp 
        INNER JOIN team t ON t.idteam = jp.idteam 
        INNER JOIN game g ON g.idgame = t.idgame
        LEFT JOIN team_members tm ON tm.idteam = t.idteam
        LEFT JOIN member m ON m.idmember = tm.idmember
        LEFT JOIN event_teams et ON et.idteam = t.idteam
        LEFT JOIN event e ON e.idevent = et.idevent
        LEFT JOIN achievement a ON a.idteam = t.idteam
    WHERE 
        jp.idmember = ? AND jp.status = 'approved'
    ORDER BY t.idteam, m.idmember, e.idevent, a.idachievement";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idmember);
    $stmt->execute();
    $result = $stmt->get_result();

    $teams = [];
    while ($row = $result->fetch_assoc()) {
      $team_id = $row['idteam'];

      // Initialize team entry if not already created
      if (!isset($teams[$team_id])) {
        $teams[$team_id] = [
          'idteam' => $team_id,
          'team_name' => $row['team_name'],
          'game_name' => $row['game_name'],
          'members' => [],
          'events' => [],
          'achievements' => []
        ];
      }

      // Add unique members to the team
      if (!empty($row['member_fullname']) && !in_array($row['member_fullname'], $teams[$team_id]['members'])) {
        $teams[$team_id]['members'][] = $row['member_fullname'];
      }

      // Add unique events to the team
      if (!empty($row['event_name']) && !in_array($row['event_name'], $teams[$team_id]['events'])) {
        $teams[$team_id]['events'][] = $row['event_name'];
      }

      // Add unique achievements to the team
      if (!empty($row['achievement_name']) && !in_array($row['achievement_name'], $teams[$team_id]['achievements'])) {
        $teams[$team_id]['achievements'][] = $row['achievement_name'];
      }
    }
    $stmt->close();
    // Return teams as a re-indexed array to avoid issues with JSON encoding
    return array_values($teams);
  }

  public function updateTeam($idteam, $teamName, $idgame, $image)
  {
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    if (!$this->validateFile($ext)) {
      $_SESSION['error'] = "Please upload a JPG or JPEG file.";
      header("Location: ../admin_homepage.php");
      exit();
    }

    $sql = "UPDATE team SET name = ?, idgame = ?, extention = ? WHERE idteam = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sisi", $teamName, $idgame, $ext, $idteam);

    if ($stmt->execute()) {
      $destination = "gambar/$idteam.$ext";
      move_uploaded_file($image['tmp_name'], $destination);
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error updating team: " . $stmt->error);
    }
  }

  public function deleteTeam($idteam)
  {
    $sql = "DELETE FROM team WHERE idteam = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idteam);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error deleting team: " . $stmt->error);
    }
  }

  public function deleteEventFromTeam($idteam, $idevent)
  {
    $sql = "DELETE FROM event_teams WHERE idteam = ? AND idevent = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $idteam, $idevent);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error deleting event team: " . $stmt->error);
    }
  }

  private function validateFile($ext)
  {
    if ($ext !== 'jpg' && $ext !== 'jpeg') {
      echo "<script>
                alert('Please upload a JPG or JPEG file.');
                window.location.href = 'admin_homepage.php';
              </script>";
      return false;
    }
    return true;
  }
}
