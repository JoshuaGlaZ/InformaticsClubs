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
    $sql = "SELECT jp.*, 
                m.fname as member_fname,
                m.lname as member_lname,
                m.username as member_username,
                t.name as team_name, 
                g.name as game_name, 
                g.description as game_desc, 
                e.name as event_name, 
                e.date as event_date, 
                e.description as event_desc,
                a.name as achievement_name, 
                a.date as achievement_date, 
                a.description as achievement_desc
            FROM join_proposal jp 
                INNER JOIN team t ON t.idteam = jp.idteam 
                INNER JOIN team_members tm on tm.idteam = t.idteam
                INNER JOIN member m on m.idmember = tm.idmember
                INNER JOIN game g on g.idgame = t.idgame
                LEFT  JOIN achievement a on a.idteam = t.idteam
                LEFT  JOIN event_teams et on  et.idteam = t.idteam
                LEFT  JOIN event e on e.idevent = et.idevent
            WHERE jp.idmember = ? AND jp.status = 'approved'
            GROUP BY m.idmember";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idmember);
    $stmt->execute();
    $result = $stmt->get_result();

    $teams = [];
    while ($row = $result->fetch_assoc()) {
      $teams[] = $row;
    }

    $stmt->close();
    return $teams;
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
