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
    $sql = "SELECT p.*, t.name 
            FROM join_proposal p 
            INNER JOIN team t ON t.idteam = p.idteam 
            WHERE p.idmember = ? AND p.status = 'approved'";
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
