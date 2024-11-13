<?php
require_once __DIR__ . '/../config/db.php';

class Game extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function addGame($gamename, $description)
  {
    $sql = "INSERT INTO game (name, description) VALUES (?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $gamename, $description);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error inserting game: " . $stmt->error);
    }
  }

  public function getGames()
  {
    $sql = "SELECT * from game";
    $result = $this->conn->query($sql);
    return $result;
  }

  public function updateGame($gameName, $desc, $idgame)
  {
    $sql = "UPDATE game SET name = ?, description = ? WHERE idgame = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ssi", $gameName, $desc, $idgame);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error updating game: " . $stmt->error);
    }
  }

  public function deleteGame($idgame)
  {
    $sql = "DELETE FROM game WHERE idgame = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idgame);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error deleting game: " . $stmt->error);
    }
  }
}
