<?php
require_once("../controllers/proposal.php");

session_start();
try {
  $team = new Proposal();

  if (isset($_POST['submit'])) {
    if (isset($_POST['approve'])) {
      $idjoin_proposal = $_POST['id'];
      $team->approveProposal($idjoin_proposal);
      $_SESSION['success'] = "Approve proposal successful!";
    } elseif (isset($_POST['reject'])) {
      $idjoin_proposal = $_POST['id'];
      $team->rejectProposal($idjoin_proposal);
      $_SESSION['success'] = "Reject proposal successful!";
    } else if (isset($_POST['join'])) {
      $idmember = $_POST['idmember'];
      $idteam = $_POST['idteam'];
      $description = $_POST['description'];
      $team->joinProposal($idmember, $idteam, $description);
      $_SESSION['success'] = "Proposal successfully submitted!";
      echo "<script>
            alert('Proposal successfully submitted!');
            window.location.href = '../index.php';
          </script>";
      exit();
    }
  }
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
}

header("Location: ../admin_homepage.php?table=join_proposal");
exit();
