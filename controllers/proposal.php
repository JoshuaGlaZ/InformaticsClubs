<?php
require_once __DIR__ . '/../config/db.php';

class Proposal extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function approveProposal($id)
  {
    $sql = "UPDATE join_proposal SET status = 'approved' WHERE idjoin_proposal = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error approving proposal: " . $stmt->error);
    }
  }

  public function rejectProposal($id)
  {
    $sql = "UPDATE join_proposal SET status = 'rejected' WHERE idjoin_proposal = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error rejecting proposal: " . $stmt->error);
    }
  }

  public function joinProposal($idmember, $idteam, $description)
  {
    $checkSql = "SELECT COUNT(*) as count FROM join_proposal WHERE idteam = ? AND idmember = ?";
    $checkStmt = $this->conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $idteam, $idmember);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
      echo "<script>
            alert('Proposal already submitted!');
            window.location.href = '../index.php';
          </script>";
      return false;
    }
    $sql = "INSERT INTO join_proposal (idmember, idteam, description, status) values(?,?,?, 'waiting')";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iis", $idmember,  $idteam, $description);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Joining proposal failed!: " . $stmt->error);
    }
  }

  public function insertTeamMembers($idmember, $idteam, $description)
  {
    $sql = "INSERT INTO team_members (idmember, idteam, description) values(?,?,?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iis", $idmember,  $idteam, $description);

    if ($stmt->execute()) {
      return true;
    } else {
      $stmt->close();
      throw new Exception("Adding members into team failed!: " . $stmt->error);
    }
  }

  public function deleteTeamMembers($idmember, $idteam)
  {
    $sql = "DELETE FROM team_members where idmember = ? AND idteam = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $idmember,  $idteam);

    if ($stmt->execute()) {
      return true;
    } else {
      $stmt->close();
      throw new Exception("Delete members into team failed!: " . $stmt->error);
    }
  }
}
