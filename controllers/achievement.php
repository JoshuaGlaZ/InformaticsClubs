<?php
require_once __DIR__ . '/../config/db.php';

class Achievement extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function addAchievement($teamid, $achievename, $achievedate, $achievedesc)
  {
    $sql = "INSERT INTO achievement (idachievement, idteam, name, date, description) VALUES(NULL, ?,?,?,?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("isss", $teamid, $achievename, $achievedate, $achievedesc);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error inserting achievement: " . $stmt->error);
    }
  }

  public function getAchievements()
  {
    $sql = "SELECT * from achievement";
    $result = $this->conn->query($sql);
    return $result;
  }

  public function updateAchievement($idteam, $namaachieve, $achievendate, $achievedesc, $idachievement)
  {
    $sql = "UPDATE achievement SET  idteam = ?,  name = ?, date = ?, description = ? WHERE idachievement = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("isssi", $idteam, $namaachieve, $achievendate, $achievedesc, $idachievement);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error updating achievement: " . $stmt->error);
    }
  }

  public function deleteAchievement($idachievement)
  {
    $sql = "DELETE FROM achievement WHERE idachievement = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idachievement);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error deleting achievement: " . $stmt->error);
    }
  }
}
