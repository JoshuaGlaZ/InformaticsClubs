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
}
