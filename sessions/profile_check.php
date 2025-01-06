<?php
session_start();
require_once __DIR__ . '/../controllers/member.php';

$username = $_SESSION['username'] ?? null;

if ($username) {
    $member = new Member();

    if (!$member->isAdmin($username)) {
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php"); 
    exit();
}

?>